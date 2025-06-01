<?php
session_start();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user'] ?? '';

    // Validate input
    if (!$user_id) {
        echo json_encode(["success" => false, "message" => "Access denied."]);
        exit;
    }

    try {
        if ($user_id) {
            $stmt = $pdo->prepare("SELECT txn_pin FROM users WHERE user_id = ?");
            $stmt->execute([$user]);
            $userPin = $stmt->fetch();

            // Retrieved transaction PIN
            $txn_pin = $userPin['txn_pin'];

            echo json_encode(["success" => true, "message", "Transaction PIN correct.", "txn_pin" => $txn_pin]);
            
        } else {
            echo json_encode(["success" => false, "message" => "Incorrect PIN."]);
        }

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}