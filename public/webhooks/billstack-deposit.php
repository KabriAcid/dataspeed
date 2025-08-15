<?php
require __DIR__ . '/../../config/config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS: allow browser-based testers and third-party services
header('Access-Control-Allow-Origin: *');
header('Vary: Origin');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Signature, X-Billstack-Signature, X-Wiaxy-Signature');

// Log all incoming requests
$logFile = __DIR__ . '/deposit-log.txt';
$timestamp = date('Y-m-d H:i:s');

// Get raw payload
$payload = file_get_contents("php://input");
$headers = getallheaders();

// Log the request
$logEntry = "[$timestamp] Webhook received:\n";
$logEntry .= "Headers: " . json_encode($headers) . "\n";
$logEntry .= "Payload: " . $payload . "\n";
$logEntry .= "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
$logEntry .= "URL: " . $_SERVER['REQUEST_URI'] . "\n";
$logEntry .= "IP: " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']) . "\n";
$logEntry .= str_repeat("-", 50) . "\n";

file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

// Handle CORS preflight early
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Verify webhook signature (if Billstack provides one)
function verifyWebhookSignature($payload, $signature, $secret)
{
    $expectedSignature = hash_hmac('sha256', $payload, $secret);
    return hash_equals($expectedSignature, $signature);
}

// Allow only POST for processing
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    file_put_contents($logFile, "[$timestamp] ERROR: Unsupported method {$_SERVER['REQUEST_METHOD']}\n", FILE_APPEND);
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Verify x-wiaxy-signature = md5(secret) where secret = BILLSTACK_SECRET_KEY
$secret = getenv('BILLSTACK_SECRET_KEY') ?: ($_ENV['BILLSTACK_SECRET_KEY'] ?? '');
if (empty($secret)) {
    file_put_contents($logFile, "[$timestamp] ERROR: Missing BILLSTACK_SECRET_KEY env for signature verification\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Webhook secret not configured']);
    exit;
}

$providedSig = null;
foreach ($headers as $hk => $hv) {
    if (strtolower($hk) === 'x-wiaxy-signature') {
        $providedSig = trim((string)$hv);
        break;
    }
}
$expectedSig = md5($secret);
if (!$providedSig || !hash_equals($expectedSig, $providedSig)) {
    file_put_contents($logFile, "[$timestamp] ERROR: Signature verification failed. Provided={$providedSig} Expected={$expectedSig}\n", FILE_APPEND);
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
    exit;
}

// Parse payload with fallbacks (JSON body -> form 'payload' -> flat form)
$contentType = $_SERVER['CONTENT_TYPE'] ?? ($_SERVER['HTTP_CONTENT_TYPE'] ?? '');
$data = json_decode($payload, true);
if (!$data) {
    if (!empty($_POST)) {
        if (isset($_POST['payload'])) {
            $data = json_decode($_POST['payload'], true);
        }
        if (!$data) {
            // Treat flat form fields as data
            $data = $_POST;
        }
    }
}

if (!$data || !is_array($data)) {
    file_put_contents($logFile, "[$timestamp] ERROR: Unable to parse payload. Content-Type={$contentType} Body=" . substr($payload, 0, 1000) . "\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid payload']);
    exit;
}

// Some providers nest fields under 'data'
$root = (isset($data['data']) && is_array($data['data'])) ? $data['data'] : $data;

// Billstack event/type (may indicate success without an explicit status)
$eventRaw = $data['event'] ?? ($root['event'] ?? '');
$event = strtolower(trim((string)$eventRaw));
$typeRaw = $root['type'] ?? '';
$type = strtolower(trim((string)$typeRaw));

// Extract expected fields with tolerances for alternative keys
$statusRaw = $root["status"] ?? $data["status"] ?? '';
$accountNumber = $root['account_number'] ?? $root['accountNo'] ?? $root['customer_account'] ?? $data['account_number'] ?? '';
// Billstack nested account number
if (!$accountNumber && isset($root['account']) && is_array($root['account']) && isset($root['account']['account_number'])) {
    $accountNumber = $root['account']['account_number'];
}
$amountRaw = $root['amount'] ?? $root['amount_paid'] ?? $data['amount'] ?? null;
$reference = $root['reference'] ?? $root['transaction_reference'] ?? $root['ref'] ?? $data['reference'] ?? '';

// Payer details for sender name
$payerFirst = trim((string)($root['payer']['first_name'] ?? ''));
$payerLast = trim((string)($root['payer']['last_name'] ?? ''));
$payerAcc = preg_replace('/\D/', '', (string)($root['payer']['account_number'] ?? ''));
$senderName = trim($payerFirst . ' ' . $payerLast);
if ($senderName === '') {
    $senderName = trim((string)($root['account']['account_name'] ?? ''));
}
if ($senderName === '') {
    $senderName = $payerAcc ? ('Acct ' . ('****' . substr($payerAcc, -4))) : 'Bank Transfer';
}
// Masked payer account for messages
$payerAcctMasked = $payerAcc ? ('****' . substr($payerAcc, -4)) : '';

// Optional extra refs
$wiaxyRef = $root['wiaxy_ref'] ?? '';
$merchantRef = $root['merchant_reference'] ?? '';

$status = strtolower(trim((string)$statusRaw));
$amount = is_numeric($amountRaw) ? (float)$amountRaw : (float)preg_replace('/[^\d.]/', '', (string)$amountRaw);

// Optional: convert minor units (e.g., kobo) to major units using env BILLSTACK_AMOUNT_SCALE
$scaleEnv = getenv('BILLSTACK_AMOUNT_SCALE') ?: ($_ENV['BILLSTACK_AMOUNT_SCALE'] ?? '1');
$scale = (int)$scaleEnv;
if ($scale > 1) {
    $amount = round($amount / $scale, 2);
}

// If status missing, infer success from Billstack event/type
$eventKey = preg_replace('/[^a-z]/', '', $event); // normalize, drop underscores/typos
$isBillstackPayment = str_starts_with($eventKey, 'paymentnotification');
if (!$status && $isBillstackPayment) {
    // For reserved account transactions, treat as successful
    if (!$type || $type === 'reserved_account_transaction') {
        $status = 'successful';
    }
}

// Log normalized fields
file_put_contents($logFile, "[$timestamp] Normalized fields: " . json_encode([
    'status' => $status,
    'account_number' => $accountNumber,
    'amount' => $amount,
    'reference' => $reference,
    'sender' => $senderName,
    'payer_account' => $payerAcc,
    'event' => $event,
    'type' => $type,
    'scale' => $scale
], JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

// Validate required fields after normalization (status may be inferred)
if (!$accountNumber || !$reference || $amount <= 0) {
    file_put_contents($logFile, "[$timestamp] ERROR: Missing/invalid required fields\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing or invalid fields']);
    exit;
}

// Determine success from status or Billstack event/type
$isSuccessful = ($status === 'successful') || ($isBillstackPayment && (!$type || $type === 'reserved_account_transaction'));
if (!$isSuccessful) {
    file_put_contents($logFile, "[$timestamp] INFO: Transaction not successful, status: {$status}\n", FILE_APPEND);
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'message' => 'Transaction not successful']);
    exit;
}

// Validate amount
if ($amount <= 0) {
    file_put_contents($logFile, "[$timestamp] ERROR: Invalid amount: $amount\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid amount']);
    exit;
}

// Sanitize reference
$reference = substr(preg_replace('/[^a-zA-Z0-9-_]/', '', $reference), 0, 100);
// we'll store raw sender in DB and escape on render
//$senderName = htmlspecialchars($senderName, ENT_QUOTES, 'UTF-8');

// Payment channel/bank from documented payload
$channel = trim((string)($root['account']['bank_name'] ?? ''));
if ($channel === '') {
    $channel = 'Bank Transfer';
}

try {
    // Find user by virtual account
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE virtual_account = ?");
    $stmt->execute([$accountNumber]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        file_put_contents($logFile, "[$timestamp] ERROR: User not found for account: $accountNumber\n", FILE_APPEND);
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit;
    }

    $userId = $user['user_id'];
    file_put_contents($logFile, "[$timestamp] INFO: Found user ID: $userId for account: $accountNumber\n", FILE_APPEND);

    // Check for duplicate transaction
    $stmt = $pdo->prepare("SELECT id FROM transactions WHERE reference = ?");
    $stmt->execute([$reference]);
    if ($stmt->fetch()) {
        file_put_contents($logFile, "[$timestamp] INFO: Duplicate transaction ignored: $reference\n", FILE_APPEND);
        http_response_code(200);
        echo json_encode(['status' => 'ok', 'message' => 'Transaction already processed']);
        exit;
    }

    // Start transaction
    $pdo->beginTransaction();

    // Get user details
    $stmt = $pdo->prepare("SELECT email, phone_number FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $userDetails['email'] ?? '';
    $phoneNumber = $userDetails['phone_number'] ?? '';

    // Update or create account balance
    $stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ? FOR UPDATE");
    $stmt->execute([$userId]);
    $balance = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($balance) {
        $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance + ?, updated_at = NOW() WHERE user_id = ?");
        $stmt->execute([$amount, $userId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO account_balance (user_id, wallet_balance, email, phone_number) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $amount, $email, $phoneNumber]);
    }

    // Log transaction
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, service_id, type, direction, amount, email, status, reference, description, icon, color, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $userId,
        5, // service_id for deposits
        'Deposit',
        'credit',
        $amount,
        $email,
        'success',
        $reference,
        trim("Billstack deposit from $senderName" . ($payerAcctMasked ? " ($payerAcctMasked)" : '') . ($wiaxyRef ? " | wiaxy_ref: $wiaxyRef" : '') . ($merchantRef ? " | merchant_ref: $merchantRef" : '')),
        'ni ni-money-coins',
        'text-success'
    ]);

    // Create notification - e.g., "N100 credit from ABUBAKAR ADAMU - OPAY"
    $title = "Deposit Received";
    $message = 'N' . number_format($amount, 0) . ' credit from ' . $senderName . ' - ' . strtoupper($channel);

    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, color, icon, is_read, created_at) VALUES (?, ?, ?, ?, ?, ?, 0, NOW())");
    $stmt->execute([$userId, $title, $message, 'deposit', 'text-success', 'ni ni-money-coins']);

    // Commit transaction
    $pdo->commit();

    file_put_contents($logFile, "[$timestamp] SUCCESS: Wallet funded for user $userId, amount $amount, reference $reference\n", FILE_APPEND);

    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Deposit processed successfully']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $errorMsg = "ERROR: " . $e->getMessage();
    file_put_contents($logFile, "[$timestamp] $errorMsg\n", FILE_APPEND);

    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Internal server error']);
}
