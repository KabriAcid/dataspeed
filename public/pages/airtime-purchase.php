<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

header('Content-Type: application/json');

// 1. Request & Auth Checks
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}
$user_id = $_SESSION['user_id'];

// 2. Collect & Validate Input
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
    // 3. Fetch User Details
    $stmt = $pdo->prepare("SELECT txn_pin, failed_attempts, account_status FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    // Check if the account is already frozen
    if ($user['account_status'] == ACCOUNT_STATUS_FROZEN) {
        echo json_encode(["success" => false, "message" => "Your account is frozen due to multiple failed PIN attempts."]);
        exit;
    }

    // Check if the transaction PIN is set
    if (empty($user['txn_pin'])) {
        echo json_encode(["success" => false, "message" => "No Transaction PIN set.", "redirect" => true]);
        exit;
    }

    // Verify the PIN
    if (!password_verify($pin, $user['txn_pin'])) {
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

// 11-digit format for VTpass
if (strlen($phone) === 10) {
    $phone = '0' . $phone;
}

$amount = (float)$amount;

// 4. Check Balance
$stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
$stmt->execute([$user_id]);
$account = $stmt->fetch();
if (!$account) {
    echo json_encode(["success" => false, "message" => "Account not found."]);
    exit;
}
$balance = (float)$account['wallet_balance'];
if ($balance < $amount) {
    echo json_encode(["success" => false, "message" => "Insufficient balance."]);
    exit;
}

// 5. Provider & Service Mapping
$providerMap = [
    'mtn'      => 1,
    'airtel'   => 2,
    'glo'      => 3,
    'etisalat' => 4,
    '9mobile'  => 4
];
$provider_id = $providerMap[$network];

$serviceMap = [
    'airtime_self'   => 1,
    'airtime_others' => 1,
    'data_self'      => 2,
    'data_others'    => 2,
    'electricity'    => 3,
    'tv'             => 4
];
$service_id = $serviceMap[$type] ?? 1;

// 6. Prepare VTpass API Call
$serviceID = $network === '9mobile' ? 'etisalat' : $network; // VTpass uses 'etisalat' for 9mobile
$request_id = time() . rand(1000, 9999);
$postData = [
    'serviceID'   => $serviceID,
    'amount'      => $amount,
    'phone'       => $phone,
    'request_id'  => $request_id
];
$vtpass_api_key = $_ENV['VTPASS_API_KEY'];
$vtpass_secret_key = $_ENV['VTPASS_SECRET_KEY'];
$vtpass_url = $_ENV['VTPASS_SANDBOX_URL'] ?? 'https://sandbox.vtpass.com/api/pay';

// If request ID exist in the db reshuffle it
$stmt = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE reference = ?");
$stmt->execute([$request_id]);
if ($stmt->fetchColumn() > 0) {
    $request_id = time() . rand(1000, 9999); // Regenerate unique request ID
}

// 7. Begin Transaction
try {
    $pdo->beginTransaction();

    // Call VTpass API first (do NOT deduct balance yet)
    $ch = curl_init($vtpass_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/x-www-form-urlencoded",
        "api-key: $vtpass_api_key",
        "secret-key: $vtpass_secret_key"
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $api_response = curl_exec($ch);
    $api_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $api_result = json_decode($api_response, true);

    // VTpass response if failed
    if ($api_http_code !== 200 || !isset($api_result['code']) || $api_result['code'] !== '000') {
        safeRollback($pdo);

        // Custom message for duplicate transaction
        if (isset($api_result['code']) && $api_result['code'] === '019') {
            $errorMsg = "This transaction appears to be a duplicate. Please check your transaction history before retrying.";
        } else {
            $errorMsg = $api_result['response_description'] ?? 'Airtime purchase failed. Please retry.';
        }

        $plan_id = null;
        $status = "failed";
        $direction = 'debit';
        $icon = 'ni ni-mobile-button';
        $color = 'text-danger';
        $description = "Airtime purchase of ₦" . number_format($amount, 2) . " for $phone on " . strtoupper($network) . " failed.";
        $title = 'Airtime Purchase Failed';
        $type = 'airtime_purchase_failed';

        // Send failed notification
        pushNotification($pdo, $user_id, $title, $description, $type, $icon, $color, '0');

        echo json_encode(["success" => false, "message" => $errorMsg]);
        exit;
    }

    // Only deduct balance if VTpass was successful
    $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance - ? WHERE user_id = ?");
    $stmt->execute([$amount, $user_id]);

    // Log successful transaction
    $plan_id = null;
    $status = "success";
    $direction = 'debit';
    $icon = 'ni ni-mobile-button';
    $color = 'text-success';
    $description = "You have purchased ₦" . number_format($amount, 2) . " airtime for $phone on " . strtoupper($network) . ".";

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, service_id, provider_id, plan_id, type, icon, color, direction, description, amount, email, reference, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $user_id,
        $service_id,
        $provider_id,
        $plan_id,
        ucfirst(str_replace('_', ' ', $type)),
        $icon,
        $color,
        $direction,
        $description,
        $amount,
        $user['email'],
        $request_id,
        $status
    ]);

    $pdo->commit();

    // Send notification
    $title = 'Airtime Purchase Successful';
    $type = 'airtime_purchase';
    pushNotification($pdo, $user_id, $title, $description, $type, $icon, $color, '0');

    echo json_encode([
        "success" => true,
        "message" => "Purchase successful!",
        "reference" => $request_id,
        "new_balance" => $balance - $amount,
        "vtpass_response" => $api_result
    ]);
} catch (PDOException $e) {
    safeRollback($pdo);
    echo json_encode(["success" => false, "message" => "Transaction failed: " . $e->getMessage()]);
}
