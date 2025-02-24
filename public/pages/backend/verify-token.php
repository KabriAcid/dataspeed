<?php

require __DIR__ . '/../../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'] ?? '';

    if (empty($token)) {
        echo json_encode(["success" => false, "message" => "Token is required."]);
        exit;
    }

    try {
        // Check if token is valid
        $stmt = $pdo->prepare("SELECT email FROM forgot_password WHERE token = ?");
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Token is valid, store email in session
            echo json_encode(["success" => true, "message" => "Token verified."]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid token."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
