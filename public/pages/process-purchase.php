<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$pin = trim($_POST['pin'] ?? '');
$amount = trim($_POST['amount'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$network = trim($_POST['network'] ?? '');
$type = trim($_POST['type'] ?? '');

// using ternary operator to set values
$type = ($type == 'Self') ? 'Airtime Self' : 'Airtime Other';

// Validate input
if (!is_numeric($amount) || $amount <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid amount."]);
    exit;
}

$amount = (float) $amount;

// Fetch user and PIN
$stmt = $pdo->prepare("SELECT user_id, txn_pin FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found."]);
    exit;
}

// Check PIN (plain or hashed, depending on your storage)
if ($user['txn_pin'] === null || $user['txn_pin'] === '') {
    echo json_encode(["success" => false, "message" => "No Transaction PIN set."]);
    exit;
}
$pinValid = false;
if (strlen($user['txn_pin']) === 4 && is_numeric($user['txn_pin'])) {
    // Plain 4-digit PIN (not recommended for production)
    $pinValid = ($pin === $user['txn_pin']);
} else {
    // Hashed PIN (recommended)
    $pinValid = password_verify($pin, $user['txn_pin']);
}
if (!$pinValid) {
    echo json_encode(["success" => false, "message" => "Incorrect PIN."]);
    exit;
}

// Check balance
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

// Deduct and log transaction (atomic)
try {
    $pdo->beginTransaction();

    // Deduct balance
    $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance - ? WHERE user_id = ?");
    $stmt->execute([$amount, $user_id]);

    // Log transaction
    $service_id = 2; // Example: 2 for Airtime, adjust as needed
    $provider_id = null; // You may want to map $network to provider_id
    $plan_id = null; // If you have a plan, set it
    $status = "success";
    
    $stmt = $pdo->prepare("SELECT email FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $email = $stmt->fetch();

    $email = $email['email'];

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, service_id, provider_id, plan_id, type, amount, email, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $user_id,
        $service_id,
        $provider_id,
        $plan_id,
        $type,
        $amount,
        $email,
        $status
    ]);

    if($pdo->commit()){
        // Insert notification for user in the db
        $title = 'Airtime Purchase Successful';
        $message = 'You have purchased airtime for ' . $amount;
        pushNotification($pdo, $user_id, $title, $message, 'airtime_purchase', 'fa-check', false);
    
        echo json_encode([
            "success" => true,
            "message" => "Purchase successful!",
            "new_balance" => $balance - $amount
        ]);
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(["success" => false, "message" => "Transaction failed: " . $e->getMessage()]);
}