<?php
function showBalance($pdo, $user_id)
{
    $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $balance = $stmt->fetch();

    return number_format($balance['wallet_balance'], 2);
}

function getTransactions($pdo, $user_id)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$user_id]);
        $transactions = $stmt->fetchAll();
        return $transactions;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getTransactionIcon($type)
{
    $icons = [
        'Airtime Purchase' => '<i class="fa fa-phone"></i>',
        'Electricity Bills' => '<i class="fa fa-lightning"></i>',
        'Deposit' => '<i class="fa fa-wallet2"></i>',
        'Data Purchase' => '<i class="fa fa-wifi"></i>',
        'Cable Subscription' => '<i class="fa fa-tv"></i>',
        'Withdrawal' => '<i class="fa fa-arrow-down-circle"></i>',
        'Default' => '<i class="fa fa-credit-card"></i>'
    ];

    return $icons[$type] ?? $icons['Default'];
}
