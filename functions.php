<?php
function showBalance($pdo, $user_id){
    $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $balance = $stmt->fetch();

    return $balance;
}