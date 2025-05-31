<?php
session_start();
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $amount = trim($_POST["amount"] ?? '');
    $user_id = $_SESSION['user'];

    try {
        $stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $account_balance = $stmt->fetch();

        $available_balance = $account_balance['wallet_balance'];

        if($available_balance >= $amount){
            echo json_encode(["success" => true, "message" => "Sufficient balance.", "balance" => $available_balance]);
        } else {
            echo json_encode(["success" => false, "message" => "Insufficient balance."]);
            exit;
        }

    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}