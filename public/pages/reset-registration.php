<?php

require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $context = $_POST['context'] ?? '';
    $email = $_POST['email'] ?? '';

    try {
        if ($context === "register") {
            // Clear registration session or temp data if any
            echo json_encode(["success" => true, "message" => "Registration reset."]);
        } elseif ($context === "pin" && $email) {
            // Remove any PIN reset tokens for this email
            $stmt = $pdo->prepare("DELETE FROM forgot_pin WHERE email = ?");
            $stmt->execute([$email]);
            echo json_encode(["success" => true, "message" => "PIN reset process cleared."]);
        } elseif ($context === "password" && $email) {
            // Remove any password reset tokens for this email
            $stmt = $pdo->prepare("DELETE FROM forgot_password WHERE email = ?");
            $stmt->execute([$email]);
            echo json_encode(["success" => true, "message" => "Password reset process cleared."]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid context or missing email."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
    exit;
}
