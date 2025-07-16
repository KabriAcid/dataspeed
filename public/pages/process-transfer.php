<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

$recipient = trim($_POST['email'] ?? '');
$amount = floatval($_POST['amount'] ?? 0);

$pin = $_POST['pin'] ?? null;

if (empty($pin)) {
    echo json_encode(["success" => false, "message" => "Transaction PIN is required."]);
    exit;
}

if (!$user_id || !$recipient || $amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if ($amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid amount.']);
    exit;
}

// Check if user has a transaction PIN set
$stmt = $pdo->prepare("SELECT txn_pin FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$userPin = $stmt->fetchColumn();

if (!$userPin) {
    echo json_encode([
        'success' => false,
        'message' => 'You need to set a transaction PIN before making transfers.',
        'redirect' => true
    ]);
    exit;
}

// Check if transaction pin is provided and valid
$providedPin = $_POST['pin'] ?? '';
if (empty($providedPin)) {
    echo json_encode([
        'success' => false,
        'message' => 'Transaction PIN is required.',
        'pin' => $providedPin
    ]);
    exit;
}
// Check if PIN is numeric and 4-6 digits (adjust as needed)
if (!ctype_digit($providedPin) || strlen($providedPin) < 4 || strlen($providedPin) > 6) {
    echo json_encode([
        'success' => false,
        'message' => 'Transaction PIN must be a 4-6 digit number.'
    ]);
    exit;
}

if (!password_verify($providedPin, $userPin)) {
    error_log("Provided PIN: " . $providedPin);
    error_log("Stored PIN Hash: " . $userPin);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid transaction PIN.'
    ]);
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
    if ($user['account_status'] == ACCOUNT_STATUS_LOCKED) {
        echo json_encode(["success" => false, "message" => "Your account is locked due to multiple failed PIN attempts."]);
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
            $stmt->execute([$failed_attempts, ACCOUNT_STATUS_LOCKED, $user_id]);

            pushNotification($pdo, $user_id, "Account Frozen", "Your account has been frozen due to multiple failed PIN attempts.", "security", "ni ni-lock-circle-open", "text-danger", 0);

            echo json_encode(["success" => false, "message" => "Your account has been frozen due to multiple failed PIN attempts.", "locked" => true]);
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

    // Fetch sender email
    $stmt = $pdo->prepare("SELECT email FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $senderEmail = $stmt->fetchColumn();

    // Fetch recipient email (already available as $recipient)
    $recipientEmail = $recipient;

    // Fetch sender name
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $sender = $stmt->fetch(PDO::FETCH_ASSOC);
    $senderName = $sender ? $sender['first_name'] . ' ' . $sender['last_name'] : 'Unknown User';

    // Fetch recipient name
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
    $stmt->execute([$recipient_id]);
    $recipient = $stmt->fetch(PDO::FETCH_ASSOC);
    $recipientName = $recipient ? $recipient['first_name'] . ' ' . $recipient['last_name'] : 'Unknown User';


    // Log transactions for both users
    $descSender = "{$recipientName}";
    $descRecipient = "{$senderName}";

    $iconSender = 'ni ni-send';
    $iconRecipient = 'ni ni-money-coins';


    $colorSender = 'text-success';
    $colorRecipient = 'text-success';

    $reference = uniqid('tf_', true);

    $pdo->prepare("INSERT INTO transactions (user_id, type, direction, amount, status, description, icon, color, reference, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
        ->execute([$user_id, 'Money Transfer', 'debit', $amount, 'success', $descSender, $iconSender, $colorSender, $reference, $senderEmail]);
    $pdo->prepare("INSERT INTO transactions (user_id, type, direction, amount, status, description, icon, color, reference, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
        ->execute([$recipient_id, 'Money Received', 'credit', $amount, 'success', $descRecipient, $iconRecipient, $colorRecipient, $reference, $recipientEmail]);

    // Push notifications
    pushNotification($pdo, $user_id, "Transfer Sent", "You sent ₦" . number_format($amount, 2) . " to {$recipientName}.", "Money Transferred", $iconSender, $colorSender, '0');
    pushNotification($pdo, $recipient_id, "Transfer Received", "You have received ₦" . number_format($amount, 2) . " from {$senderName}.", "Money Received", $iconRecipient, $colorRecipient, '0');

    $pdo->commit();

    $stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $new_balance = $stmt->fetchColumn();

    echo json_encode([
        'success' => true,
        'message' => 'Transfer successful!',
        'reference' => $reference // <-- Add this line
    ]);
    exit;
} catch (Exception $e) {
    safeRollback($pdo);
    echo json_encode(['success' => false, 'message' => 'Transfer failed.']);
}
