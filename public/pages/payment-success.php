<?php
session_start();
require __DIR__ . '/../../config/config.php';

if (!isset($_GET['transaction_id'])) {
    echo json_encode(["success" => false, "message" => "Transaction ID missing"]);
    exit;
}

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['user_id'];
}

$transaction_id = $_GET['transaction_id'];
$FLW_SECRET_KEY = $_ENV['FLW_SECRET_KEY'];

// Make a cURL request to verify transaction status
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$transaction_id}/verify",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $FLW_SECRET_KEY",
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo json_encode(["success" => false, "message" => "cURL Error: $err"]);
    exit;
}

$transaction = json_decode($response, true);

// Check if the transaction is completed
if ($transaction['status'] == "success" && $transaction['data']['status'] == "successful") {
    $payment_status = $transaction['data']['status'] ?? '';
    $amount = $transaction['data']['amount'] ?? 0;
    $user_email = $transaction['data']['customer']['email'] ?? '';
    $transaction_type = "Deposit";

    // Insert transaction details into the database
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ? FOR UPDATE");
        $stmt->execute([$user_id]);
        $accountRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$accountRow) {
            $stmt = $pdo->prepare("INSERT INTO account_balance (user_id, wallet_balance) VALUES (?, ?)");
            $stmt->execute([$user_id, $amount]);
        } else {
            $current_balance = $accountRow['wallet_balance'] + $amount;
            $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = ?, updated_at = NOW() WHERE user_id = ?");
            $stmt->execute([$current_balance, $user_id]);
        }

        $pdo->commit();


        echo "<script>
            setTimeout(function() {
                window.location.href = 'dashboard.php';
            }, 1000);
        </script>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Transaction not completed"]);
    exit;
}