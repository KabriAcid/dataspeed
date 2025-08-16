<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/initialize.php';

header('Content-Type: application/json');

try {
    $limit = isset($_GET['limit']) ? max(1, min(10, (int)$_GET['limit'])) : 5;
    $txs = getTransactions($pdo, $user_id, $limit);
    $formatted = array_map(function ($t) {
        $icon = $t['icon'] ?? '';
        $color = $t['color'] ?? '';
        if ($icon && $color && strpos($icon, 'ni ') === false) {
            // Fallback: build icon markup if only class names were stored
            $icon = "<i class='ni {$icon} {$color}'></i>";
        }
        return [
            'type' => (string)($t['type'] ?? ''),
            'direction' => (string)($t['direction'] ?? ''),
            'amount' => (float)($t['amount'] ?? 0),
            'date' => date('h:i A . d F, Y.', strtotime($t['created_at'] ?? 'now')),
            'icon' => $icon,
        ];
    }, $txs ?: []);

    echo json_encode(['success' => true, 'transactions' => $formatted]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error fetching transactions']);
}
