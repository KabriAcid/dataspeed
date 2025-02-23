<?php

require __DIR__ . '/../../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $token = $_POST['token'] ?? '';

    try {
        // Check if the OTP is valid
        $stmt = $pdo->prepare("SELECT * FROM forgot_password WHERE email = ? AND token = ?");
        $stmt->execute([$email, $token]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            echo json_encode(["success" => true, "message" => "Token verified successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid or expired Token."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
