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
            error_log("No account_balance row for user_id: $user_id");
            echo json_encode(["success" => false, "message" => "Account not found."]);
            exit;
        }

        $available_balance = isset($account_balance['wallet_balance']) ? (float)$account_balance['wallet_balance'] : 0.0;
        error_log("User $user_id: wallet_balance = $available_balance, amount checked = $amount");

        if ($available_balance >= $amount) {
            echo json_encode([
                "success" => true,
                "message" => "Sufficient balance.",
                "balance" => $available_balance
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Insufficient balance.",
                "balance" => $available_balance
            ]);
            exit;
        }
    } catch (PDOException $e) {
        error_log("DB error: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
