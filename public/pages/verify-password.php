<?php

require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'] ?? '';
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(["success" => false, "message" => "User not authenticated."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(["success" => false, "message" => "User not found."]);
            exit;
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            echo json_encode(["success" => false, "message" => "Incorrect password."]);
            exit;
        }

        echo json_encode(["success" => true, "message" => "Password verified."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
