<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$rewards = getReferralRewards($pdo, $user_id);

echo json_encode([
    'success' => true,
    'data' => [
        'pending' => number_format($rewards['pending'], 2),
        'claimed' => number_format($rewards['claimed'], 2)
    ]
]);
