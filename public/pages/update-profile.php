<?php
session_start();
require_once __DIR__ . '/../../config/config.php'; // Update to your path

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['step'] ?? '') === 'address') {
    $state = trim($_POST['state'] ?? '');
    $lga = trim($_POST['lga'] ?? '');
    $userId = $_SESSION['user'] ?? null;

    if (!$state || !$lga || !$userId) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    try {
        // Save to DB (PDO assumed)
        $stmt = $pdo->prepare("UPDATE users SET state = ?, lga = ? WHERE user_id = ?");
        $updated = $stmt->execute([$state, $lga, $userId]);

        echo json_encode(['success' => $updated]);
        exit;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}