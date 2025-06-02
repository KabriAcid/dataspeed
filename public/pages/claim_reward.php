<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

if (!isset($_POST['referral_id'])) {
    echo json_encode(['success' => false, 'message' => 'No referral ID provided']);
    exit;
}

$referral_id = (int) $_POST['referral_id'];

try {
    // Fetch the referral and validate it
    $stmt = $pdo->prepare("SELECT reward FROM referrals WHERE referral_id = ? AND user_id = ? AND status = 'pending'");
    $stmt->execute([$referral_id, $user_id]);
    $referral = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$referral) {
        echo json_encode(['success' => false, 'message' => 'Invalid referral or already claimed.']);
        exit;
    }

    $reward = (float) $referral['reward'];

    // Begin transaction
    $pdo->beginTransaction();

    // Update referral status
    $stmt = $pdo->prepare("UPDATE referrals SET status = 'claimed' WHERE referral_id = ? AND user_id = ?");
    $stmt->execute([$referral_id, $user_id]);

    if ($stmt->rowCount() === 0) {
        // No rows updated, rollback and fail
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to claim reward.']);
        exit;
    }

    // Update wallet balance
    $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance + ? WHERE user_id = ?");
    $stmt->execute([$reward, $user_id]);

    // Commit transaction
    $pdo->commit();

    // Fetch updated referral info (after commit)
    $referral = getReferralById($pdo, $referral_id);
    $user = getUserInfo($pdo, $referral['user_id']);

    // Push notification (outside transaction is okay)
    $title = 'Referral Reward';
    $message = 'Congratulations! You have successfully claimed your ₦' . number_format($reward, 2) . ' referral bonus.';
    // pushNotification($pdo, $user_id, $title, $message, 'referral_bonus', 'fa-referral', false);

    // Send JSON response
    echo json_encode([
        'success' => true,
        'message' => 'Reward claimed successfully!'
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}