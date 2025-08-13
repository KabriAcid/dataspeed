<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';

header('Content-Type: application/json');

session_start();

// Debug logging
$debug_log = __DIR__ . '/../../debug-balance.log';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get current balance
    $balance = getUserBalance($pdo, $user_id);

    if ($balance !== false) {
        $response = [
            'success' => true,
            'balance' => number_format($balance, 2, '.', ''),
            'formatted_balance' => number_format($balance, 2),
            'timestamp' => time()
        ];
        echo json_encode($response);
    } else {
        file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] ERROR: Could not retrieve balance\n", FILE_APPEND);
        echo json_encode([
            'success' => false,
            'message' => 'Could not retrieve balance',
            'balance' => '0.00'
        ]);
    }
} catch (Exception $e) {
    $error = "Balance update error: " . $e->getMessage();
    error_log($error);
    file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] EXCEPTION: $error\n", FILE_APPEND);
    echo json_encode([
        'success' => false,
        'message' => 'Server error',
        'balance' => '0.00'
    ]);
}
