<?php

require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $registration_id = $_POST['registration_id'] ?? '';

    if (empty($registration_id)) {
        echo json_encode(["success" => false, "message" => "Registration ID is not set."]);
        exit;
    }

    try {
        // Check if token is valid
        $stmt = $pdo->prepare("DELETE FROM users WHERE registration_id = ?");
        $stmt->execute([$registration_id]);

        echo json_encode(["success" => true, "message" => "Registration reset successful."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
