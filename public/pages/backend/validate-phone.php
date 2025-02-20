<?php
require __DIR__ . '/../../../config/config.php';
session_start();

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phone = trim($_POST["phone"] ?? '');

    // Ensure user has a session registration ID
    if (!isset($_SESSION['registration_id'])) {
        echo json_encode(["success" => false, "message" => "Session expired. Restart registration."]);
        exit;
    }

    $registration_id = $_SESSION['registration_id'];

    // Normalize phone number (Handle users omitting first '0')
    if (preg_match('/^(234)?([789][01]\d{8})$/', $phone, $matches)) {
        $phone = "234" . $matches[2]; // Convert to full international format
    } else {
        echo json_encode(["success" => false, "message" => "Invalid Nigerian phone number."]);
        exit;
    }

    try {
        // Check if the phone number already exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE phone_number = ?");
        $stmt->execute([$phone]);

        if ($stmt->fetch()) {
            echo json_encode(["success" => false, "message" => "Phone number already in use."]);
            exit;
        }

        // Update phone number for the current registration
        $stmt = $pdo->prepare("UPDATE users SET phone_number = ? WHERE registration_id = ?");
        $stmt->execute([$phone, $registration_id]);

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error. Please try again later."]);
    }
}
