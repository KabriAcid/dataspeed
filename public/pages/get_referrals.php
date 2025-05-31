<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

header('Content-Type: application/json');

// Assuming user is logged in and user_id is in session
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

try {
    // Fetch pending referrals for this user
    $stmtPending = $pdo->prepare("SELECT id AS referral_id, reward, status, DATE_FORMAT(created_at, '%M %d, %Y %h:%i %p') AS created_at FROM referrals WHERE user_id = ? AND status = 'Pending'");
    $stmtPending->execute([$user_id]);
    $pending = $stmtPending->fetchAll(PDO::FETCH_ASSOC);

    // Fetch completed (claimed) referrals
    $stmtCompleted = $pdo->prepare("SELECT id AS referral_id, reward, status, DATE_FORMAT(created_at, '%M %d, %Y %h:%i %p') AS created_at FROM referrals WHERE user_id = ? AND status = 'Claimed'");
    $stmtCompleted->execute([$user_id]);
    $completed = $stmtCompleted->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'pending' => $pending,
            'completed' => $completed,
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching referrals: ' . $e->getMessage()
    ]);
}