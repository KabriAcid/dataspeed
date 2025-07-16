<?php
session_start();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phone = $_POST['phone'] ?? '';
    $registration_id = $_POST['registration_id'] ?? '';

    // Check if country code is present
    if (!isset($phone) || empty($phone)) {
        echo json_encode(["success" => false, "message" => "Phone number is required."]);
        exit;
    }

    // Check if phone number is too short
    if (strlen(preg_replace('/\D/', '', $phone)) <= 9) {
        echo json_encode(["success" => false, "message" => "Phone number is too short. Enter at least 10 digits."]);
        exit;
    }
    
    if (preg_match('/^(\+234|234)/', $phone)) {
        echo json_encode(["success" => false, "message" => "Remove the country code."]);
        exit;
    }

    // Sanitize input to remove leading zero if present
    $phone = preg_replace('/^0/', '', $phone);

    // Validate: must be exactly 10 digits now
    if (!preg_match('/^\d{10}$/', $phone)) {
        echo json_encode(["success" => false, "message" => "Enter a valid Nigerian phone number (10 digits, no leading 0)."]);
        exit;
    }

    // Always store with leading zero
    $phone = '0' . $phone;

    try {
        // Check if the phone number already exists and its registration status
        $stmt = $pdo->prepare("SELECT registration_status FROM users WHERE phone_number = ?");
        $stmt->execute([$phone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['registration_status'] === 'complete') {
                echo json_encode(["success" => false, "message" => "Phone number already registered."]);
                exit;
            } else {
                echo json_encode(["success" => true, "message" => "Resuming incomplete registration."]);
            }
        } else {
            // If the phone number is valid and not already registered, update users table using registration_id
            $stmt = $pdo->prepare("UPDATE users SET phone_number = ? WHERE registration_id = ?");
            $stmt->execute([$phone, $registration_id]);

            echo json_encode(["success" => true, "message" => "Phone number is valid and updated."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
