<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$pin = trim($_POST['pin'] ?? '');
$amount = trim($_POST['amount'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$network = trim($_POST['network'] ?? '');
$type = trim($_POST['type'] ?? 'change');
$plan_id = trim($_POST['plan_id'] ?? '');
$iuc = trim($_POST['iuc'] ?? '');

if (!is_numeric($amount) || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid amount.']);
    exit;
}
if (strlen($pin) !== 4 || !ctype_digit($pin)) {
    echo json_encode(['success' => false, 'message' => 'PIN must be a 4-digit number.']);
    exit;
}
if (!preg_match('/^\d{6,12}$/', $iuc)) {
    echo json_encode(['success' => false, 'message' => 'Invalid IUC/Smartcard number.']);
    exit;
}
if (!preg_match('/^0\d{10}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Invalid phone number.']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT txn_pin, failed_attempts, account_status, email FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception('User not found.');
    }

    if ($user['account_status'] == ACCOUNT_STATUS_LOCKED) {
        throw new Exception('Your account is locked due to multiple failed PIN attempts.');
    }

    if (!password_verify($pin, $user['txn_pin'])) {
        $failed_attempts = $user['failed_attempts'] + 1;
        if ($failed_attempts >= 5) {
            $stmt = $pdo->prepare("UPDATE users SET failed_attempts = ?, account_status = ? WHERE user_id = ?");
            $stmt->execute([$failed_attempts, ACCOUNT_STATUS_LOCKED, $user_id]);

            pushNotification($pdo, $user_id, "Account Locked", "Your account has been locked due to multiple failed PIN attempts.", "security", "ni ni-lock-circle-open", "text-danger", 0);

            throw new Exception("Your account has been locked due to multiple failed PIN attempts.");
        }
        $stmt = $pdo->prepare("UPDATE users SET failed_attempts = ? WHERE user_id = ?");
        $stmt->execute([$failed_attempts, $user_id]);

        throw new Exception("Incorrect PIN. You have " . (5 - $failed_attempts) . " attempts remaining.");
    }

    $stmt = $pdo->prepare("UPDATE users SET failed_attempts = 0 WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $account = $stmt->fetch();
    if (!$account || (float)$account['wallet_balance'] < $amount) {
        throw new Exception('Insufficient balance.');
    }

    $providerMap = ['dstv' => 5, 'gotv' => 6, 'startimes' => 7, 'showmax' => 8];
    $provider_id = $providerMap[strtolower($network)] ?? 0;
    $service_id = 3;
    $request_id = generateRequestID('TV', $user_id, $pdo);

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
        $data = ['token' => $token, 'expires_at' => time() + (6 * 24 * 60 * 60)];
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


    $token = getEbillToken($ebills_username, $ebills_password);
    if (!$token) {
        throw new Exception('Failed to authenticate with eBills.');
    }

    $url = $ebills_base_url . '/api/v2/tv';
    $payload = json_encode([
        'request_id' => $request_id,
        'customer_id' => $iuc,
        'service_id' => strtolower($network),
        'variation_id' => $plan_id,
        'subscription_type' => $type,
        'amount' => (int)$amount
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
    if ($http_code !== 200 || ($result['code'] ?? '') !== 'success') {
        throw new Exception($result['message'] ?? 'TV subscription failed.');
    }

    $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance - ? WHERE user_id = ?");
    $stmt->execute([$amount, $user_id]);

    $desc = "TV subscription of ₦" . number_format($amount, 2) . " for IUC $iuc on " . strtoupper($network);
    $icon = 'ni ni-tv-2';
    $color = 'text-success';
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, service_id, provider_id, plan_id, type, icon, color, direction, description, amount, email, reference, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $service_id, $provider_id, $plan_id, 'TV Subscription', $icon, $color, 'debit', $desc, $amount, $user['email'], $request_id, 'success']);

    pushNotification($pdo, $user_id, "TV Subscription Successful", $desc, 'tv_subscription', $icon, $color, 0);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'TV subscription successful.',
        'reference' => $request_id,
        'new_balance' => (float)$account['wallet_balance'] - $amount,
        'response' => $result
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
