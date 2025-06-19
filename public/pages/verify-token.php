<?php

require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'] ?? '';
    $type = $_POST['type'] ?? 'password';

    if (empty($token)) {
        echo json_encode(["success" => false, "message" => "Token is required."]);
        exit;
    }

    // Choose table based on type
    $table = ($type === 'pin') ? 'forgot_pin' : 'forgot_password';

    try {
        // Check if token is valid in the correct table
        $stmt = $pdo->prepare("SELECT token FROM `$table` WHERE token = ?");
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode(["success" => true, "message" => "Token verified."]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid token."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
