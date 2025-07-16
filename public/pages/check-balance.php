<?php
session_start();
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "Unauthorized access."]);
        exit;
    }

    $amount = trim($_POST["amount"] ?? '');
    
    if (!is_numeric($amount) || $amount <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid amount."]);
        exit;
    }
    $amount = (float)$amount;
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $account_balance = $stmt->fetch();

        if (!$account_balance) {
            echo json_encode(["success" => false, "message" => "Account not found."]);
            exit;
        }

        $available_balance = (float)$account_balance['wallet_balance'];

        if ($available_balance >= $amount) {
            echo json_encode([
                "success" => true,
                "message" => "Sufficient balance."
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Insufficient balance."]);
            exit;
        }

    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
