<?php
session_start();
header('Content-Type: application/json');

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user'];
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

        echo json_encode(['success' => true, 'message' => 'Account updated.']);
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

        echo json_encode(['success' => true, 'message' => 'Address updated.']);
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
