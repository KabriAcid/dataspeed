<?php

require __DIR__ . '/../../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'] ?? '';
    $reset_email = $_POST['reset_email'] ?? '';

    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Update the user's password
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $reset_email]);

        // Remove the token from forgot_password table
        $stmt = $pdo->prepare("DELETE FROM forgot_password WHERE email = ?");
        $stmt->execute([$reset_email]);

        echo json_encode(["success" => true, "message" => "Password reset successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
