<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Verify webhook signature (if Billstack provides one)
function verifyWebhookSignature($payload, $signature, $secret)
{
    $expectedSignature = hash_hmac('sha256', $payload, $secret);
    return hash_equals($expectedSignature, $signature);
}

// Parse JSON payload
$data = json_decode($payload, true);

if (!$data) {
    file_put_contents($logFile, "[$timestamp] ERROR: Invalid JSON payload\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    exit;
}

// Log parsed data
file_put_contents($logFile, "[$timestamp] Parsed data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

// Validate required fields
$requiredFields = ['status', 'account_number', 'amount', 'reference'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        file_put_contents($logFile, "[$timestamp] ERROR: Missing required field: $field\n", FILE_APPEND);
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Missing field: $field"]);
        exit;
    }
}

// Check if transaction is successful
if (strtolower($data['status']) !== 'successful') {
    file_put_contents($logFile, "[$timestamp] INFO: Transaction not successful, status: {$data['status']}\n", FILE_APPEND);
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'message' => 'Transaction not successful']);
    exit;
}

$accountNumber = $data['account_number'];
$amount = floatval($data['amount']);
$reference = $data['reference'];
$sender = $data['sender_name'] ?? 'Bank Transfer';

// Validate amount
if ($amount <= 0) {
    file_put_contents($logFile, "[$timestamp] ERROR: Invalid amount: $amount\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid amount']);
    exit;
}

// Sanitize reference
$reference = substr(preg_replace('/[^a-zA-Z0-9-_]/', '', $reference), 0, 100);
$sender = htmlspecialchars($sender, ENT_QUOTES, 'UTF-8');

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
        "Deposit from $sender",
        'ni ni-money-coins',
        'text-success'
    ]);

    // Create notification
    $title = "Deposit Received";
    $message = "₦" . number_format($amount, 2) . " has been credited to your wallet from $sender.";

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
