<?php
session_start();
header('Content-Type: application/json');
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';

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
        $user_name = trim($_POST['user_name'] ?? '');

        if (!$bank || !$account || strlen($account) < 10) {
            throw new Exception('Please provide a valid bank and 10-digit account number.');
        }
        // Username must not start with a number, must not include spaces, and must not be more than 20 characters
        if (strlen($user_name) <= 4 || strlen($user_name) > 20) {
            echo json_encode(["success" => false, "message" => "Invalid username length"]);
            exit;
        }
        if (preg_match('/^\d/', $user_name)) {
            echo json_encode(["success" => false, "message" => "Username must not start with a number."]);
            exit;
        }
        if (preg_match('/\s/', $user_name)) {
            echo json_encode(["success" => false, "message" => "Username must not contain spaces."]);
            exit;
        }

        $stmt = $pdo->prepare("SELECT user_name FROM users WHERE user_name = ?");
        $stmt->execute([$user_name]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode(["success" => false, "message" => "Username is already taken."]);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE users SET w_bank_name = ?, w_account_number = ?, user_name = ? WHERE user_id = ?");
        $stmt->execute([$bank, $account, $user_name, $user_id]);

        // Push notification for bank account update
        $title = "Account Details Updated";
        $message = "Your account details were updated successfully.";
        $type = "profile";
        $icon = "ni ni-single-02";
        $color = "text-info";
        pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, '0');

        echo json_encode(['success' => true, 'message' => 'Account details updated.']);
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
        $title = "Home address Updated";
        $message = "Your address details were updated successfully.";
        $type = "profile";
        $icon = "ni ni-pin-3";
        $color = "text-dark";
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
