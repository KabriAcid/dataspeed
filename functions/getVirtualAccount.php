<?php
require __DIR__ . '/../config/config.php';

function getUserVirtualAccount($pdo, $userId)
{
    $stmt = $pdo->prepare("SELECT account_number, bank_name FROM virtual_accounts WHERE user_id = :user_id LIMIT 1");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
