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
        'Data' => '<i class="fa fa-wifi text-danger"></i>',
        'Airtime' => '<i class="fa fa-phone text-primary"></i>',
        'Deposit' => '<i class="fa fa-credit-card text-success"></i>',
        'Withdrawal' => '<i class="fa fa-arrow-down text-danger"></i>',
        'Default' => '<i class="fa fa-credit-card"></i>'
    ];

    return $icons[$type] ?? $icons['Default'];
}

function getReferrals($pdo, $user_id)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE referral_code = ?");
        $stmt->execute([$user_id]);
        $referrals = $stmt->fetchAll();
        return $referrals;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// A function for retrieving user referral details from the database
function getUserReferralDetails($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM referrals WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
    