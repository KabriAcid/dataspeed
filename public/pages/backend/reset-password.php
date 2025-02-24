<?php

require __DIR__ . '/../../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'] ?? '';
    $token = $_POST['token'] ?? '';
    $email = $_SESSION['reset_email'] ?? '';

    if (empty($password) || empty($token) || empty($email)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if (strlen($password) < 8) {
        echo json_encode(["success" => false, "message" => "Password must be at least 8 characters long."]);
        exit;
    }

    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Update the user's password
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);

        // Remove the token from forgot_password table
        $stmt = $pdo->prepare("DELETE FROM forgot_password WHERE email = ?");
        $stmt->execute([$email]);

        echo json_encode(["success" => true, "message" => "Password reset successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
