<?php
session_start();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user'] ?? '';
    $submitted_pin = trim($_POST['pin'] ?? '');

    // Validate session and input
    if (!$user_id || $submitted_pin === '') {
        echo json_encode(["success" => false, "message" => "Access denied or PIN missing."]);
        exit;
    }

    try {
        // Fetch hashed PIN from DB
        $stmt = $pdo->prepare("SELECT txn_pin FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($submitted_pin, $user['txn_pin'])) {
            echo json_encode(["success" => true, "message" => "PIN verified."]);
        } else {
            echo json_encode(["success" => false, "message" => "Incorrect PIN."]);
        }

    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
        ]);
    }
}
