<?php

require __DIR__ . '/../../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'] ?? '';
    $registration_id = $_POST['registration_id'] ?? '';

    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Update the user's password and set registration status to complete
        $stmt = $pdo->prepare("UPDATE users SET password = ?, registration_status = 'complete' WHERE registration_id = ?");
        $stmt->execute([$hashedPassword, $registration_id]);
        echo json_encode(["success" => true, "message" => "Password updated successfully. Registration complete."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
