<?php
// Ensure clean JSON output for fetch() consumers
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ob_start();

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');

session_start();

function jsonResponse(array $payload, int $code = 200): void
{
    http_response_code($code);
    if (ob_get_length()) {
        ob_clean();
    }
    echo json_encode($payload);
    exit;
}

// Debug logging
$debug_log = __DIR__ . '/../../debug-balance.log';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    jsonResponse(['success' => false, 'message' => 'User not authenticated'], 401);
}

$user_id = $_SESSION['user_id'];

try {
    // Get current balance
    $balance = getUserBalance($pdo, $user_id);

    if ($balance !== false) {
        // Normalize numeric formatting in case getUserBalance returns a formatted string
        $numericBalance = (float)str_replace([',', ' '], '', (string)$balance);
        jsonResponse([
            'success' => true,
            'balance' => number_format($numericBalance, 2, '.', ''),
            'formatted_balance' => number_format($numericBalance, 2),
            'timestamp' => time()
        ]);
    } else {
        file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] ERROR: Could not retrieve balance\n", FILE_APPEND);
        jsonResponse([
            'success' => false,
            'message' => 'Could not retrieve balance',
            'balance' => '0.00'
        ]);
    }
} catch (Exception $e) {
    $error = "Balance update error: " . $e->getMessage();
    error_log($error);
    file_put_contents($debug_log, "[" . date('Y-m-d H:i:s') . "] EXCEPTION: $error\n", FILE_APPEND);
    jsonResponse([
        'success' => false,
        'message' => 'Server error',
        'balance' => '0.00'
    ], 500);
}
