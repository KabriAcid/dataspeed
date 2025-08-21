<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$service_id = trim($_POST['service_id'] ?? '');
$variation_id = trim($_POST['variation_id'] ?? ''); // prepaid/postpaid
$meter = trim($_POST['meter'] ?? '');
$amount = floatval($_POST['amount'] ?? 0);

if (!$service_id || !$variation_id || !$meter || $amount < 1000 || $amount > 100000) {
    echo json_encode(['success' => false, 'message' => 'Invalid input. Amount must be between ₦1,000 and ₦100,000.']);
    exit;
}

// Check user balance
$stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
$stmt->execute([$user_id]);
$account = $stmt->fetch();
if (!$account || $account['wallet_balance'] < $amount) {
    echo json_encode(['success' => false, 'message' => 'Insufficient balance.']);
    exit;
}
$balance = (float)$account['wallet_balance'];

// Generate unique request_id
$request_id = generateRequestID('EB', $user_id, $pdo);

$ebills_username = $_ENV['EBILLS_USERNAME'] ?? '';
$ebills_password = $_ENV['EBILLS_PASSWORD'] ?? '';
$ebills_base_url = $_ENV['EBILLS_BASE_URL'] ?? 'https://ebills.africa/wp-json';

function getEbillToken($username, $password)
{
    // ...use your existing token cache logic...
    $ch = curl_init("$GLOBALS[ebills_base_url]/jwt-auth/v1/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['username' => $username, 'password' => $password]));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['token'] ?? null;
}

$token = getEbillToken($ebills_username, $ebills_password);
if (!$token) {
    echo json_encode(['success' => false, 'message' => 'Failed to authenticate with eBills.']);
    exit;
}

$url = "$ebills_base_url/api/v2/electricity";
$payload = json_encode([
    'request_id' => $request_id,
    'customer_id' => $meter,
    'service_id' => $service_id,
    'variation_id' => $variation_id,
    'amount' => $amount
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($http_code !== 200) {
    echo json_encode(['success' => false, 'message' => $result['message'] ?? 'Purchase failed.']);
    exit;
}

$status = $result['order_status'] ?? '';
$data = $result['data'] ?? [];
$token_val = $data['token'] ?? null;
$units = $data['units'] ?? null;
$band = $data['band'] ?? null;

try {
    $pdo->beginTransaction();

    // Deduct wallet balance only if not refunded
    if ($status !== 'ORDER REFUNDED') {
        $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance - ? WHERE user_id = ?");
        $stmt->execute([$amount, $user_id]);
    }

    // Insert transaction record
    $stmt = $pdo->prepare("INSERT INTO transactions (request_id, user_id, service_id, variation_code, customer_id, amount, token, units, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $request_id,
        $user_id,
        $service_id,
        $variation_id,
        $meter,
        $amount,
        $token_val,
        $units,
        $status
    ]);

    // Refund logic
    if ($status === 'ORDER REFUNDED') {
        $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance + ? WHERE user_id = ?");
        $stmt->execute([$amount, $user_id]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => $result['message'] ?? 'Purchase processed.',
        'order_status' => $status,
        'token' => $token_val,
        'units' => $units,
        'band' => $band,
        'customer_name' => $data['customer_name'] ?? '',
        'address' => $data['address'] ?? '',
        'amount' => $amount,
        'reference' => $request_id
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
}
