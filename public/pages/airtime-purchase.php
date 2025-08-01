<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

header('Content-Type: application/json');

$ebills_username = $_ENV['EBILLS_USERNAME'] ?? '';
$ebills_password = $_ENV['EBILLS_PASSWORD'] ?? '';
$ebills_base_url = $_ENV['EBILLS_BASE_URL'] ?? 'https://ebills.africa/wp-json';
$token_cache_file = __DIR__ . '/../../cache/ebills_token.json';

function getCachedToken()
{
    global $token_cache_file;
    if (!file_exists($token_cache_file)) return null;

    $data = json_decode(file_get_contents($token_cache_file), true);
    if (!$data || !isset($data['token']) || !isset($data['expires_at'])) return null;

    if (time() >= $data['expires_at']) return null;

    return $data['token'];
}

function saveTokenToCache($token)
{
    global $token_cache_file;
    $data = [
        'token' => $token,
        'expires_at' => time() + (6 * 24 * 60 * 60) // 6 days expiry
    ];
    file_put_contents($token_cache_file, json_encode($data));
}

function getEbillToken($username, $password, $forceRefresh = false)
{
    global $token_cache_file;

    if (!$forceRefresh) {
        $cached = getCachedToken();
        if ($cached) return $cached;
    }

    $auth_url = $GLOBALS['ebills_base_url'] . '/jwt-auth/v1/token';
    $payload = json_encode(["username" => $username, "password" => $password]);

    $ch = curl_init($auth_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    $res = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($res, true);

    if (isset($result['token'])) {
        saveTokenToCache($result['token']);
        return $result['token'];
    }

    return null;
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}
$user_id = $_SESSION['user_id'];

$pin    = trim($_POST['pin'] ?? '');
$amount = trim($_POST['amount'] ?? '');
$phone  = trim($_POST['phone'] ?? '');
$network = trim($_POST['network'] ?? '');
$type   = trim($_POST['type'] ?? '');

if (!is_numeric($amount) || $amount <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid amount."]);
    exit;
}
if (!preg_match('/^0\d{10}$/', $phone)) {
    echo json_encode(["success" => false, "message" => "Invalid phone number."]);
    exit;
}
if (!in_array($network, ['mtn', 'airtel', 'glo', 'etisalat', '9mobile'])) {
    echo json_encode(["success" => false, "message" => "Invalid network."]);
    exit;
}
if (strlen($pin) !== 4 || !ctype_digit($pin)) {
    echo json_encode(["success" => false, "message" => "Invalid PIN format."]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT email, txn_pin, failed_attempts, account_status FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    if ($user['account_status'] == ACCOUNT_STATUS_LOCKED) {
        echo json_encode(["success" => false, "message" => "Your account is locked due to multiple failed PIN attempts.", "locked" => true]);
        exit;
    }

    if (empty($user['txn_pin'])) {
        echo json_encode(["success" => false, "message" => "No Transaction PIN set.", "redirect" => true]);
        exit;
    }

    if (!password_verify($pin, $user['txn_pin'])) {
        $failed_attempts = $user['failed_attempts'] + 1;
        if ($failed_attempts >= 5) {
            $stmt = $pdo->prepare("UPDATE users SET failed_attempts = ?, account_status = ? WHERE user_id = ?");
            $stmt->execute([$failed_attempts, ACCOUNT_STATUS_LOCKED, $user_id]);
            pushNotification($pdo, $user_id, "Account Frozen", "Your account has been frozen due to multiple failed PIN attempts.", "security", "ni ni-lock-circle-open", "text-danger", 0);
            echo json_encode(["success" => false, "message" => "Your account has been frozen.", "locked" => true]);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE users SET failed_attempts = ? WHERE user_id = ?");
        $stmt->execute([$failed_attempts, $user_id]);
        echo json_encode(["success" => false, "message" => "Incorrect PIN. Attempts left: " . (5 - $failed_attempts)]);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE users SET failed_attempts = 0 WHERE user_id = ?");
    $stmt->execute([$user_id]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    exit;
}

$amount = (float)$amount;

$stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
$stmt->execute([$user_id]);
$account = $stmt->fetch();
if (!$account || $account['wallet_balance'] < $amount) {
    echo json_encode(["success" => false, "message" => "Insufficient balance."]);
    exit;
}
$balance = (float)$account['wallet_balance'];

$request_id = generateRequestID('AT', $user_id, $pdo);
$token = getEbillToken($ebills_username, $ebills_password);
if (!$token) {
    echo json_encode(["success" => false, "message" => "Authentication with eBills failed."]);
    exit;
}

// Admin wallet balance check
$ch = curl_init($ebills_base_url . '/api/v2/balance');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);
$balanceResponse = curl_exec($ch);
curl_close($ch);

$balanceData = json_decode($balanceResponse, true);

if (isset($balanceData['code']) && $balanceData['code'] === 'rest_forbidden') {
    // Token was invalidated, regenerate and retry once
    $token = getEbillToken($ebills_username, $ebills_password, true);

    $ch = curl_init($ebills_base_url . '/api/v2/balance');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);
    $balanceResponse = curl_exec($ch);
    curl_close($ch);

    $balanceData = json_decode($balanceResponse, true);
}

if (!isset($balanceData['data']['balance'])) {
    echo json_encode(["success" => false, "message" => "Unable to check reseller balance. Try again later."]);
    exit;
}


$reseller_balance = (float) $balanceData['data']['balance'];
if ($reseller_balance < $amount) {
    echo json_encode(["success" => false, "message" => "Transaction could not be completed at the moment. Please try again later."]);
    exit;
}


$payload = json_encode([
    "request_id" => $request_id,
    "service_id" => $network,
    "phone"      => $phone,
    "amount"     => $amount
]);

$ch = curl_init($ebills_base_url . '/api/v2/airtime/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($http_code !== 200 || ($result['code'] ?? '') !== 'success') {
    pushNotification($pdo, $user_id, 'Airtime Purchase Failed', $result['message'] ?? 'Failed.', 'airtime_failed', 'ni ni-mobile-button', 'text-danger', 0);
    echo json_encode(["success" => false, "message" => $result['message'] ?? 'Airtime purchase failed.']);
    error_log('Airtime purchase error: ' . $response);
    exit;
}

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance - ? WHERE user_id = ?");
    $stmt->execute([$amount, $user_id]);

    $description = "You purchased ₦" . number_format($amount, 2) . " airtime for $phone on " . strtoupper($network);
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, service_id, provider_id, plan_id, type, icon, color, direction, description, amount, email, reference, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $user_id,
        1,
        1,
        null,
        'Airtime Purchase',
        'ni ni-mobile-button',
        'text-success',
        'debit',
        $description,
        $amount,
        $user['email'],
        $request_id,
        'success'
    ]);
    $pdo->commit();

    pushNotification($pdo, $user_id, 'Airtime Purchase Successful', $description, 'airtime_purchase', 'ni ni-mobile-button', 'text-success', 0);
    echo json_encode(["success" => true, "message" => "Airtime purchased successfully.", "reference" => $request_id, "new_balance" => $balance - $amount]);
} catch (PDOException $e) {
    safeRollback($pdo);
    echo json_encode(["success" => false, "message" => "Transaction failed: " . $e->getMessage()]);
}
