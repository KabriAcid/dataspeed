<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user'];

try {
    // Update all pending referrals to claimed
    $stmt = $pdo->prepare("UPDATE referrals SET status = 'claimed' WHERE user_id = ? AND status = 'pending'");
    $stmt->execute([$user_id]);

    // Update the user's balance
    $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance + (SELECT SUM(reward) FROM referrals WHERE user_id = ? AND status = 'claimed') WHERE user_id = ?");
    $stmt->execute([$user_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Reward claimed successfully!']);

        // Push notification
        $title = 'Referral Reward';
        $message = 'Congratulations! You have successfully redeemed your referral bonus';
        pushNotification($pdo, $user_id, $title, $message, 'referral_bonus', 'fa-referral', false);
    } else {
        echo json_encode(['success' => false, 'message' => 'No pending rewards to claim.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}