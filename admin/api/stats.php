<?php
session_start();
header('Content-Type: application/json');

// Check authentication
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require __DIR__ . '/../../config/config.php';

try {
    // Get dashboard statistics
    $stats = [];

    // Active Users (users who logged in within last 30 days)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND status = 'active'");
    $stats['active_users'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);

    // Today's Revenue
    $stmt = $pdo->query("SELECT COALESCE(SUM(amount), 0) as total FROM transactions WHERE DATE(created_at) = CURDATE() AND status = 'completed'");
    $stats['today_revenue_amount'] = (float)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // New Users Today
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = CURDATE()");
    $stats['new_users_today'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);

    // Total Users' Balance (sum of account_balance.wallet_balance for active users)
    $stmt = $pdo->query("SELECT COALESCE(SUM(ab.wallet_balance), 0) AS total
                         FROM account_balance ab
                         INNER JOIN users u ON u.id = ab.user_id
                         WHERE u.status = 'active'");
    $stats['total_users_balance'] = (float)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    // Pending Transactions
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM transactions WHERE status = 'pending'");
    $stats['pending_transactions'] = (int)($stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0);

    // Daily Transactions (last 7 days)
    $stmt = $pdo->query("
        SELECT 
            DATE(created_at) as date,
            COALESCE(SUM(amount), 0) as amount
        FROM transactions 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        AND status = 'completed'
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    $dailyTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fill missing dates with 0
    $dailyData = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $found = false;
        foreach ($dailyTransactions as $transaction) {
            if ($transaction['date'] === $date) {
                $dailyData[] = [
                    'date' => date('M j', strtotime($date)),
                    'amount' => (float)$transaction['amount']
                ];
                $found = true;
                break;
            }
        }
        if (!$found) {
            $dailyData[] = [
                'date' => date('M j', strtotime($date)),
                'amount' => 0
            ];
        }
    }

    // Bill Distribution
    $stmt = $pdo->query("
        SELECT 
            service_type as label,
            COUNT(*) as value
        FROM transactions 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        AND status = 'completed'
        GROUP BY service_type
        ORDER BY value DESC
    ");
    $billDistribution = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map service types to readable labels
    $serviceLabels = [
        'data' => 'Data',
        'airtime' => 'Airtime',
        'electricity' => 'Electricity',
        'cable_tv' => 'Cable TV',
        'betting' => 'Betting'
    ];

    foreach ($billDistribution as &$item) {
        $item['label'] = $serviceLabels[$item['label']] ?? ucfirst($item['label']);
        $item['value'] = (int)$item['value'];
    }

    $response = [
        'success' => true,
        'data' => array_merge($stats, [
            'series' => [
                'daily_transactions' => $dailyData,
                'bill_distribution' => $billDistribution
            ]
        ])
    ];

    echo json_encode($response);
} catch (PDOException $e) {
    error_log("Stats API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch statistics'
    ]);
}
