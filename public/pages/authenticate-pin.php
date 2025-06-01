<?php
session_start();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user'] ?? '';
    $submitted_pin = trim($_POST['pin'] ?? '');

    if (!$user_id || !$submitted_pin) {
        echo json_encode(["success" => false, "message" => "Access denied or missing PIN."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT txn_pin FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $userPin = $stmt->fetch();

        if (!$userPin) {
            echo json_encode(["success" => false, "message" => "User not found."]);
            exit;
        }

        $stored_pin = $userPin['txn_pin'];

        if ($submitted_pin === $stored_pin) {
            echo json_encode(["success" => true, "message" => "Transaction PIN correct."]);
        } else {
            echo json_encode(["success" => false, "message" => "Incorrect PIN."]);
        }

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
