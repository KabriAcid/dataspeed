<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
$recipient = trim($_POST['email'] ?? '');
$amount = floatval($_POST['amount'] ?? 0);

if (!$user_id || !$recipient || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Find recipient and check registration status
$stmt = $pdo->prepare("SELECT user_id, first_name, last_name, registration_status FROM users WHERE email = ? AND registration_status = 'complete' LIMIT 1");
$stmt->execute([$recipient]);
$recipientData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipientData) {
    echo json_encode(['success' => false, 'message' => 'Beneficiary not found.']);
    exit;
}
if ($recipientData['registration_status'] !== 'complete') {
    echo json_encode(['success' => false, 'message' => 'Beneficiary registration is not complete.']);
    exit;
}
$recipient_id = $recipientData['user_id'];

// Check sender balance
$stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
$stmt->execute([$user_id]);
$balance = $stmt->fetchColumn();

if ($balance < $amount) {
    echo json_encode(['success' => false, 'message' => 'Insufficient balance.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Deduct from sender
    $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance - ? WHERE user_id = ?")
        ->execute([$amount, $user_id]);

    // Credit recipient
    $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance + ? WHERE user_id = ?")
        ->execute([$amount, $recipient_id]);

    // Log transactions for both users
    $descSender = "Transfer to {$recipient}";
    $descRecipient = "Received transfer from user $user_id";
    $icon = 'ni-send';
    $colorSender = 'text-warning';
    $colorRecipient = 'text-success';

    $pdo->prepare("INSERT INTO transactions (user_id, type, amount, status, description, icon, color) VALUES (?, ?, ?, ?, ?, ?, ?)")
        ->execute([$user_id, 'Money Transfer', $amount, 'success', $descSender, $icon, $colorSender]);
    $pdo->prepare("INSERT INTO transactions (user_id, type, amount, status, description, icon, color) VALUES (?, ?, ?, ?, ?, ?, ?)")
        ->execute([$recipient_id, 'Money Transfer', $amount, 'success', $descRecipient, $icon, $colorRecipient]);

    // Push notifications
    pushNotification($pdo, $user_id, "Transfer Sent", "You sent ₦" . number_format($amount, 2) . " to {$recipient}.", "Money Transferred", $icon, $colorSender, '0');
    pushNotification($pdo, $recipient_id, "Transfer Received", "You received ₦" . number_format($amount, 2) . " from user $recipient.", "Money Received", $icon, $colorRecipient, '0');

    $pdo->commit();

    $stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $new_balance = $stmt->fetchColumn();

    echo json_encode(['success' => true, 'message' => 'Transfer successful!', 'new_balance' => $new_balance]);
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Transfer failed.']);
}
