<?php
session_start();
header('Content-Type: application/json');

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$step = $_POST['step'] ?? '';

try {

    if ($step === 'account') {
        $bank = trim($_POST['bank_name'] ?? '');
        $account = trim($_POST['account_number'] ?? '');

        if (!$bank || !$account || strlen($account) < 10) {
            throw new Exception('Please provide a valid bank and 10-digit account number.');
        }

        $stmt = $pdo->prepare("UPDATE users SET w_bank_name = ?, w_account_number = ? WHERE user_id = ?");
        $stmt->execute([$bank, $account, $user_id]);

        // Push notification for bank account update
        $title = "Bank Account Updated";
        $message = "Your withdrawal bank account details were updated successfully.";
        $type = "profile";
        $icon = "ni-building";
        $color = "text-info";
        pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, '0');

        echo json_encode(['success' => true, 'message' => 'Bank Account updated.']);
        exit;

    } elseif ($step === 'address') {
        $state = trim($_POST['state'] ?? '');
        $lga = trim($_POST['lga'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (!$state || !$lga || !$address) {
            throw new Exception('Please fill in state, LGA, and address.');
        }

        $stmt = $pdo->prepare("UPDATE users SET state = ?, city = ?, address = ? WHERE user_id = ?");
        $stmt->execute([$state, $lga, $address, $user_id]);

        // Push notification for address update
        $title = "Address Updated";
        $message = "Your address details were updated successfully.";
        $type = "profile";
        $icon = "ni-pin-3";
        $color = "text-primary";
        pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, '0');

        echo json_encode(['success' => true, 'message' => 'Home Address updated.']);
        exit;

    } elseif ($step === 'biodata') {
        // If this step should not allow updates, return an error
        echo json_encode(['success' => false, 'message' => 'Biodata is not editable.']);
        exit;

    } else {
        throw new Exception('Invalid step.');
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
