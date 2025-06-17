<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';

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
    $stmt = $pdo->prepare("SELECT reward FROM referral_reward WHERE referral_id = ? AND user_id = ? AND status = 'pending'");
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
    $stmt = $pdo->prepare("UPDATE referral_reward SET status = 'claimed' WHERE referral_id = ? AND user_id = ?");
    $stmt->execute([$referral_id, $user_id]);

    if ($stmt->rowCount() === 0) {
        // No rows updated, rollback and fail
        safeRollback($pdo);
        echo json_encode(['success' => false, 'message' => 'Failed to claim reward.']);
        exit;
    }

    // Update wallet balance
    $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance + ? WHERE user_id = ?");
    $stmt->execute([$reward, $user_id]);

    // Insert into transactions table
    $service_id = 5;
    $type = 'Referral Reward';
    $status = 'success';
    $description = 'referral';
    $icon = 'ni ni-money-coins';
    $color = 'text-info';
    $direction = 'credit';

    $insertTxn = $pdo->prepare("INSERT INTO transactions (user_id, service_id, type, direction, amount, status, description, icon, color) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insertTxn->execute([
        $user_id,
        $service_id,
        $type,
        $direction,
        $reward,
        $status,
        $description,
        $icon,
        $color
    ]);

    // Commit transaction
    $pdo->commit();

    // Fetch updated referral info (after commit)
    $referral = getReferralById($pdo, $referral_id);
    $user = getUserInfo($pdo, $referral['user_id']);

    // Push notification (outside transaction is okay)
    $title = 'Referral Reward Claimed';
    $message = 'Congratulations! You have successfully claimed your ₦' . number_format($reward, 2) . ' referral bonus.';
    $type = 'referral';
    $icon = 'ni ni-trophy';
    $color = 'text-info';
    pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, '0');
    // Send JSON response
    echo json_encode([
        'success' => true,
        'message' => 'Reward claimed successfully!'
    ]);
} catch (PDOException $e) {
    safeRollback($pdo);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
