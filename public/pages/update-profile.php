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
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
$step = $_POST['step'] ?? '';

try {

    if ($step === 'account') {
        // Sanitize inputs (store raw text, not HTML-encoded)
        $kyc_type = strtoupper(trim(strip_tags($_POST['kyc_type'] ?? '')));
        $kyc_value = trim(strip_tags($_POST['kyc_value'] ?? ''));
        $user_name = trim($_POST['user_name'] ?? '');

        // Basic presence checks
        if ($kyc_type === '' || $kyc_value === '' || $user_name === '') {
            throw new Exception('Please provide KYC type, KYC value, and username.');
        }

        // KYC type: must be one of allowed values (all caps for ENUM)
        $allowed_types = ['BVN', 'NIN'];
        if (!in_array($kyc_type, $allowed_types)) {
            throw new Exception('Invalid KYC type selected.');
        }

        // KYC value: basic validation (length, digits for BVN/NIN, etc)
        if ($kyc_type === 'BVN' && !preg_match('/^\d{11}$/', $kyc_value)) {
            throw new Exception('BVN must be exactly 11 digits.');
        }
        if ($kyc_type === 'NIN' && !preg_match('/^\d{11}$/', $kyc_value)) {
            throw new Exception('NIN must be exactly 11 digits.');
        }

        // Username rules: start with a letter, 5-20 chars, allowed [A-Za-z0-9._-]
        if (!preg_match('/^[A-Za-z][A-Za-z0-9._-]{4,19}$/', $user_name)) {
            echo json_encode(["success" => false, "message" => "Username must start with a letter, be 5-20 characters, and contain only letters, numbers, '.', '_' or '-'."]);
            exit;
        }

        // Uniqueness: ensure no other user uses this username
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_name = ? AND user_id <> ? LIMIT 1");
        $stmt->execute([$user_name, $user_id]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode(["success" => false, "message" => "Username is already taken."]);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE users SET kyc_type = ?, kyc_value = ?, user_name = ? WHERE user_id = ?");
        $stmt->execute([$kyc_type, $kyc_value, $user_name, $user_id]);

        // Push notification for KYC update
        $title = "KYC Details Updated";
        $message = "Your KYC details were updated successfully.";
        $type = "profile";
        $icon = "ni ni-single-02";
        $color = "text-info";
        pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, '0');

        echo json_encode(['success' => true, 'message' => 'KYC details updated.']);
        exit;
    } elseif ($step === 'address') {
        // Sanitize inputs (store raw text, not HTML-encoded)
        $state = trim(strip_tags($_POST['state'] ?? ''));
        $lga = trim(strip_tags($_POST['lga'] ?? ''));
        $address = trim(strip_tags($_POST['address'] ?? ''));

        if ($state === '' || $lga === '' || $address === '') {
            throw new Exception('Please fill in state, LGA, and address.');
        }

        // Optional: basic length checks
        if (strlen($state) < 2 || strlen($state) > 50) {
            throw new Exception('Please select a valid state.');
        }
        if (strlen($lga) < 2 || strlen($lga) > 50) {
            throw new Exception('Please select a valid city/LGA.');
        }
        if (strlen($address) < 5 || strlen($address) > 120) {
            throw new Exception('Address should be between 5 and 120 characters.');
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
