<?php
session_start();
header('Content-Type: application/json');

// Auth guard
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require __DIR__ . '/../../config/config.php';
// + providers
require __DIR__ . '/../../functions/ebills.php';
require __DIR__ . '/../../functions/billstack.php';

// Defaults to always return valid JSON
$stats = [
    'active_users' => 0,
    'today_revenue_amount' => 0.0,
    'new_users_today' => 0,
    'total_users_balance' => 0.0,
    'pending_transactions' => 0,
];

// 1) Active users (supports string or numeric status)
try {
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM users WHERE account_status = '101'");
    $row = $stmt->fetch();
    $stats['active_users'] = (int)($row['count'] ?? 0);
} catch (PDOException $e) {
    error_log('stats: active_users failed: ' . $e->getMessage());
}

// 2) Today’s revenue (success variants)
try {
    $stmt = $pdo->query("
        SELECT COALESCE(SUM(amount),0) AS total
        FROM transactions
        WHERE DATE(created_at) = CURDATE()
          AND status IN ('success','successful','completed')
    ");
    $row = $stmt->fetch();
    $stats['today_revenue_amount'] = (float)($row['total'] ?? 0);
} catch (PDOException $e) {
    error_log('stats: today_revenue_amount failed: ' . $e->getMessage());
}

// 3) New users today
try {
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM users WHERE DATE(created_at) = CURDATE()");
    $row = $stmt->fetch();
    $stats['new_users_today'] = (int)($row['count'] ?? 0);
} catch (PDOException $e) {
    error_log('stats: new_users_today failed: ' . $e->getMessage());
}

// 4) Total users’ balance (prefer account_balance join on active users)
try {
    $stmt = $pdo->query("
        SELECT COALESCE(SUM(ab.wallet_balance),0) AS total
        FROM account_balance ab
        INNER JOIN users u ON u.user_id = ab.user_id
        WHERE u.account_status = 101
    ");
    $row = $stmt->fetch();
    $stats['total_users_balance'] = (float)($row['total'] ?? 0);
} catch (PDOException $e) {
    error_log('stats: total_users_balance via account_balance failed, fallback: ' . $e->getMessage());
    // Safe fallback: sum all balances from account_balance without status gating
    try {
        $stmt = $pdo->query("SELECT COALESCE(SUM(wallet_balance),0) AS total FROM account_balance");
        $row = $stmt->fetch();
        $stats['total_users_balance'] = (float)($row['total'] ?? 0);
    } catch (PDOException $e2) {
        error_log('stats: account_balance fallback failed: ' . $e2->getMessage());
    }
}

// 5) Pending/processing transactions
try {
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM transactions WHERE status IN ('pending','processing')");
    $row = $stmt->fetch();
    $stats['pending_transactions'] = (int)($row['count'] ?? 0);
} catch (PDOException $e) {
    error_log('stats: pending_transactions failed: ' . $e->getMessage());
}

// 6) Daily transactions (last 7 days)
$dailyTransactions = [];
try {
    $stmt = $pdo->query("
        SELECT DATE(created_at) AS date, COALESCE(SUM(amount),0) AS amount
        FROM transactions
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
          AND status IN ('success','successful','completed')
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    $dailyTransactions = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('stats: daily_transactions failed: ' . $e->getMessage());
}

// Normalize to fixed 7-day window
$dailyData = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $match = array_values(array_filter($dailyTransactions, fn($r) => ($r['date'] ?? '') === $date));
    $dailyData[] = [
        'date' => date('M j', strtotime($date)),
        'amount' => $match ? (float)$match[0]['amount'] : 0.0
    ];
}

// 7) Bill distribution (by transactions.type over 30 days)
$billDistribution = [];
try {
    $stmt = $pdo->query("
        SELECT `type` AS label, COUNT(*) AS value
        FROM transactions
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
          AND status IN ('success','successful','completed')
        GROUP BY `type`
        ORDER BY value DESC
    ");
    $billDistribution = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('stats: bill_distribution failed: ' . $e->getMessage());
}

// Map labels and cast counts
$typeLabels = [
    'data' => 'Data',
    'airtime' => 'Airtime',
    'electricity' => 'Electricity',
    'cable_tv' => 'Cable TV',
    'betting' => 'Betting'
];
foreach ($billDistribution as &$item) {
    $raw = $item['label'] ?? '';
    $item['label'] = $typeLabels[$raw] ?? ucfirst(str_replace('_', ' ', (string)$raw));
    $item['value'] = (int)($item['value'] ?? 0);
}
unset($item);

// Final JSON
echo json_encode([
    'success' => true,
    'data' => array_merge($stats, [
        'series' => [
            'daily_transactions' => $dailyData,
            'bill_distribution'  => $billDistribution
        ],
        // + provider balances (nullable)
        'providers' => [
            '
            '    => ebills_get_balance(60),
            'billstack' => billstack_get_balance(60),
        ]
    ])
]);
