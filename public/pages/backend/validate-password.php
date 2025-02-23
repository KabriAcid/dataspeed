<?php

require __DIR__ . '/../../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'] ?? '';
    $registration_id = $_POST['registration_id'] ?? '';

    // Validate input
    if (empty($password)) {
        echo json_encode(["success" => false, "message" => "Password is required."]);
        exit;
    }

    if (strlen($password) < 8) {
        echo json_encode(["success" => false, "message" => "Password must be at least 8 characters long."]);
        exit;
    }

    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Update the user's password using registration_id
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE registration_id = ?");
        $stmt->execute([$hashedPassword, $registration_id]);

        echo json_encode(["success" => true, "message" => "Password updated successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
