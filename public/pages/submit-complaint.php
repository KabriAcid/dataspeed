<?php
session_start();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reason = trim($_POST['reason']) ?? '';

    // Validate input
    if (empty($reason)) {
        echo json_encode(["success" => false, "message" => "Please select a reason."]);
        exit;
    }

    try {

        $stmt = $pdo->prepare("SELECT reason FROM users WHERE reason = ?");
        $stmt->execute([$reason]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode(["success" => false, "message" => "Username is already taken."]);
            exit;
        }

        // Update the username using registration_id
        $stmt = $pdo->prepare("UPDATE users SET reason = ? WHERE registration_id = ?");
        $stmt->execute([$reason, $registration_id]);

        echo json_encode(["success" => true, "message" => "Username added successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
