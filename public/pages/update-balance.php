<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';

header('Content-Type: application/json');

session_start();

// Debug logging
$debug_log = __DIR__ . '/../../debug-balance.log';
file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] Balance check requested\n", FILE_APPEND);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] ERROR: User not authenticated\n", FILE_APPEND);
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];
file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] User ID: $user_id\n", FILE_APPEND);

try {
    // Get current balance
    $balance = getUserBalance($pdo, $user_id);
    file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] Balance retrieved: $balance\n", FILE_APPEND);

    if ($balance !== false) {
        $response = [
            'success' => true,
            'balance' => number_format($balance, 2, '.', ''),
            'formatted_balance' => number_format($balance, 2),
            'timestamp' => time()
        ];
        file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] Response: " . json_encode($response) . "\n", FILE_APPEND);
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
