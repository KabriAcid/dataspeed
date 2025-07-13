<?php
// filepath: c:\xampp\htdocs\dataspeed\public\pages\update-balance.php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

try {
    $balance = getUserBalance($pdo, $user_id);
    echo json_encode(['success' => true, 'balance' => $balance]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to retrieve balance.']);
}
