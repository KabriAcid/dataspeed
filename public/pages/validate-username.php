<?php


session_start();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_name = $_POST['user_name'] ?? '';
    $registration_id = $_POST['registration_id'] ?? '';

    // Validate input
    if (empty($user_name)) {
        echo json_encode(["success" => false, "message" => "Username is required."]);
        exit;
    }

    try {

        $stmt = $pdo->prepare("SELECT user_name FROM users WHERE user_name = ?");
        $stmt->execute([$user_name]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode(["success" => false, "message" => "Username is already taken."]);
            exit;
        }

        // Update the username using registration_id
        $stmt = $pdo->prepare("UPDATE users SET user_name = ? WHERE registration_id = ?");
        $stmt->execute([$user_name, $registration_id]);

        echo json_encode(["success" => true, "message" => "Username added successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}