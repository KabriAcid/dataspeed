<?php
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';

// Log raw input for debugging
$payload = file_get_contents("php://input");
file_put_contents(__DIR__ . '/deposit-log.txt', $payload . PHP_EOL, FILE_APPEND);

$data = json_decode($payload, true);

// Basic checks
if (!$data || ($data['status'] ?? '') !== 'successful') {
    file_put_contents(__DIR__ . '/deposit-log.txt', "Invalid payload\n", FILE_APPEND);
    http_response_code(400);
    exit;
}

$accountNumber = $data['account_number'] ?? '';
$amount = floatval($data['amount'] ?? 0);
if ($amount <= 0) {
    file_put_contents(__DIR__ . '/deposit-log.txt', "Invalid amount: $amount\n", FILE_APPEND);
    http_response_code(400);
    exit;
}

$reference = $data['reference'] ?? '';
$sender = $data['sender_name'] ?? 'Deposit';

$reference = substr(preg_replace('/[^a-zA-Z0-9-_]/', '', $reference), 0, 100);
$sender = htmlspecialchars($sender, ENT_QUOTES, 'UTF-8');


// Find user by virtual account
$userId = getUserIdByAccount($accountNumber);

if (!$userId) {
    file_put_contents(__DIR__ . '/deposit-log.txt', "User not found for account $accountNumber\n", FILE_APPEND);
    http_response_code(404);
    exit;
}

// Prevent duplicate credit by checking transactions table
$exists = $pdo->prepare("SELECT id FROM transactions WHERE reference = ?");
$exists->execute([$reference]);
if ($exists->fetch()) {
    file_put_contents(__DIR__ . '/deposit-log.txt', "Already credited: $reference\n", FILE_APPEND);
    http_response_code(200);
    exit;
}

try {
    $pdo->beginTransaction();

    // Fetch user email/phone if needed
    if (empty($email) || empty($phoneNumber)) {
        $userStmt = $pdo->prepare("SELECT email, phone_number FROM users WHERE user_id = ?");
        $userStmt->execute([$userId]);
        $userRow = $userStmt->fetch();
        $email = $userRow['email'] ?? '';
        $phoneNumber = $userRow['phone_number'] ?? '';
    }

    // Update or insert account_balance
    if ($balRow) {
        $update = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance + ? WHERE user_id = ?");
        $update->execute([$amount, $userId]);
    } else {
        $insertBal = $pdo->prepare("INSERT INTO account_balance (user_id, wallet_balance, email, phone_number) VALUES (?, ?, ?, ?)");
        $insertBal->execute([$userId, $amount, $email, $phoneNumber]);
    }

    // Log transaction in transactions table
    $service_id = 5;
    $type = 'deposit';
    $status = 'success';
    $insertTxn = $pdo->prepare("INSERT INTO transactions (user_id, service_id, type, amount, email, status, reference) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertTxn->execute([$userId, $service_id, $type, $amount, $email, $status, $reference]);

    // Push notification

    $title = "Deposit Received";
    $msg = "₦" . number_format($amount, 2) . " credited to your wallet from $sender.";
    $type = "deposit";
    $icon = "ni-money-coins";
    $color = "text-dark"; // Bootstrap green for successful deposit

    pushNotification($pdo, $userId, $title, $msg, $type, $icon, $color, '0');
    $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, color, icon) VALUES (?, ?, ?, ?, ?, ?)")
        ->execute([$userId, $title, $msg, $type, $color, $icon]);

    $pdo->commit();

    file_put_contents(__DIR__ . '/deposit-log.txt', "Wallet funded for user $userId, amount $amount, ref $reference\n", FILE_APPEND);
    http_response_code(200);
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    file_put_contents(__DIR__ . '/deposit-log.txt', "Error: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    exit;
}