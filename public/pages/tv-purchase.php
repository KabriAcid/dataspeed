<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';

header('Content-Type: application/json');

// 1. Auth & Method Check
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}
$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT txn_pin, failed_attempts, account_status FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    // Check if account is already frozen
    if ($user['account_status'] == ACCOUNT_STATUS_FROZEN) {
        echo json_encode(["success" => false, "message" => "Your account is frozen due to multiple failed PIN attempts."]);
        exit;
    }

    // Verify the PIN
    if (!password_verify($entered_pin, $user['txn_pin'])) {
        // Increment failed attempts
        $failed_attempts = $user['failed_attempts'] + 1;

        // Freeze account if failed attempts reach 5
        if ($failed_attempts >= 5) {
            $stmt = $pdo->prepare("UPDATE users SET failed_attempts = ?, account_status = ? WHERE user_id = ?");
            $stmt->execute([$failed_attempts, ACCOUNT_STATUS_FROZEN, $user_id]);

            pushNotification($pdo, $user_id, "Account Frozen", "Your account has been frozen due to multiple failed PIN attempts.", "security", "ni ni-lock-circle-open", "text-danger", 0);

            echo json_encode(["success" => false, "message" => "Your account has been frozen due to multiple failed PIN attempts.", "frozen" => true]);
            exit;
        }

        // Update failed attempts
        $stmt = $pdo->prepare("UPDATE users SET failed_attempts = ? WHERE user_id = ?");
        $stmt->execute([$failed_attempts, $user_id]);

        echo json_encode(["success" => false, "message" => "Incorrect PIN. You have " . (5 - $failed_attempts) . " attempts remaining."]);
        exit;
    }

    // Reset failed attempts on successful PIN entry
    $stmt = $pdo->prepare("UPDATE users SET failed_attempts = 0 WHERE user_id = ?");
    $stmt->execute([$user_id]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    exit;
}

// 2. Collect & Validate Input
$pin      = trim($_POST['pin'] ?? '');
$amount   = trim($_POST['amount'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$network  = trim($_POST['network'] ?? '');
$type     = trim($_POST['type'] ?? '');
$plan_id  = trim($_POST['plan_id'] ?? '');
$iuc      = trim($_POST['iuc'] ?? '');

if (!is_numeric($amount) || $amount <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid amount."]);
    exit;
}

if (strlen($pin) !== 4 || !ctype_digit($pin)) {
    echo json_encode(["success" => false, "message" => "PIN must be a 4-digit number."]);
    exit;
}

if (!preg_match('/^\d{10}$/', $iuc)) {
    echo json_encode(["success" => false, "message" => "Invalid IUC/Smartcard number."]);
    exit;
}

if (!preg_match('/^0\d{10}$/', $phone)) {
    echo json_encode(["success" => false, "message" => "Invalid phone number.", "phone" => $phone]);
    exit;
}

// Normalize & Cast
$amount = (float)$amount;
$phone  = strlen($phone) === 10 ? '0' . $phone : $phone;

// 3. Fetch User & PIN Check
$stmt = $pdo->prepare("SELECT user_id, txn_pin, email FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found."]);
    exit;
}

if (empty($user['txn_pin'])) {
    echo json_encode(["success" => false, "message" => "No Transaction PIN set."]);
    exit;
}

$pinValid = password_verify($pin, $user['txn_pin']) || $pin === $user['txn_pin'];
if (!$pinValid) {
    echo json_encode(["success" => false, "message" => "Incorrect Transaction PIN."]);
    exit;
}

// 4. Balance Check
$stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
$stmt->execute([$user_id]);
$account = $stmt->fetch();
if (!$account || (float)$account['wallet_balance'] < $amount) {
    echo json_encode(["success" => false, "message" => "Insufficient balance."]);
    exit;
}

// 5. Provider & Service Mapping
$providerMap = [
    'dstv' => 5,
    'gotv' => 6,
    'startimes' => 7,
    'showmax' => 8
];
$provider_id = $providerMap[strtolower($network)] ?? 0;
$service_id = 3; // Always 3 for TV

// 6. VTpass API Setup
$request_id = time() . rand(1000, 9999); // Unique request ID
$postData = [
    'serviceID'      => strtolower($network),
    'billersCode'    => $iuc,
    'variation_code' => $plan_id,
    'amount'         => $amount,
    'phone'          => $phone,
    'request_id'     => $request_id
];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE reference = ?");
$stmt->execute([$request_id]);
if ($stmt->fetchColumn() > 0) {
    $request_id = time() . rand(1000, 9999); // Regenerate unique request ID
}

$vtpass_api_key = $_ENV['VTPASS_API_KEY'];
$vtpass_secret_key = $_ENV['VTPASS_SECRET_KEY'];
$vtpass_url = $_ENV['VTPASS_SANDBOX_URL'] ?? 'https://sandbox.vtpass.com/api/pay';

try {
    $pdo->beginTransaction();

    // VTpass API Call
    $ch = curl_init($vtpass_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "api-key: $vtpass_api_key",
        "secret-key: $vtpass_secret_key",
        "Content-Type: application/x-www-form-urlencoded"
    ]);
    $api_response = curl_exec($ch);
    $api_result = json_decode($api_response, true);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200 || ($api_result['code'] ?? '') !== '000') {
        safeRollback($pdo);
        $errorMsg = $api_result['response_description'] ?? "TV subscription failed.";
        $icon = 'ni ni-tv-2';
        $color = 'text-danger';
        $desc = "TV subscription of ₦" . number_format($amount, 2) . " for IUC $iuc on " . strtoupper($network) . " failed.";


        pushNotification($pdo, $user_id, "TV Subscription Failed", $desc, 'tv_subscription_failed', $icon, $color, 0);
        echo json_encode(["success" => false, "message" => $errorMsg]);
        exit;
    }

    // Deduct wallet
    $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance - ? WHERE user_id = ?");
    $stmt->execute([$amount, $user_id]);

    // Log transaction
    $icon = 'ni ni-tv-2';
    $color = 'text-success';
    $desc = "You purchased ₦" . number_format($amount, 2) . " TV subscription for IUC $iuc on " . strtoupper($network) . ".";

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, service_id, provider_id, plan_id, type, icon, color, direction, description, amount, email, reference, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $service_id, $provider_id, $plan_id, 'TV Subscription', $icon, $color, 'debit', $desc, $amount, $user['email'], $request_id, 'success']);

    $pdo->commit();

    pushNotification($pdo, $user_id, "TV Subscription Successful", $desc, 'tv_subscription', $icon, $color, 0);
    echo json_encode([
        "success" => true,
        "message" => "TV subscription successful!",
        "reference" => $request_id,
        "vtpass_response" => $api_result,
        "new_balance" => (float)$account['wallet_balance'] - $amount
    ]);
} catch (PDOException $e) {
    safeRollback($pdo);
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
