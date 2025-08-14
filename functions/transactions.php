<?php
require_once '../config/config.php';

function getTransactions($page = 1, $limit = 20, $filters = []) {
    global $pdo;
    
    $offset = ($page - 1) * $limit;
    $where = [];
    $params = [];
    
    if (!empty($filters['status'])) {
        $where[] = "t.status = ?";
        $params[] = $filters['status'];
    }
    
    if (!empty($filters['type'])) {
        $where[] = "t.service_type = ?";
        $params[] = $filters['type'];
    }
    
    if (!empty($filters['provider'])) {
        $where[] = "t.provider LIKE ?";
        $params[] = "%{$filters['provider']}%";
    }
    
    if (!empty($filters['date_from'])) {
        $where[] = "DATE(t.created_at) >= ?";
        $params[] = $filters['date_from'];
    }
    
    if (!empty($filters['date_to'])) {
        $where[] = "DATE(t.created_at) <= ?";
        $params[] = $filters['date_to'];
    }
    
    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    
    // Get total count
    $countQuery = "SELECT COUNT(*) FROM transactions t $whereClause";
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();
    
    // Get transactions
    $query = "SELECT t.*, u.name as user_name, u.email as user_email 
              FROM transactions t 
              LEFT JOIN users u ON t.user_id = u.id 
              $whereClause 
              ORDER BY t.created_at DESC 
              LIMIT $limit OFFSET $offset";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $transactions = $stmt->fetchAll();
    
    return [
        'transactions' => $transactions,
        'total' => $total,
        'pages' => ceil($total / $limit),
        'current_page' => $page
    ];
}

function getTransactionById($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT t.*, u.name as user_name, u.email as user_email, u.phone as user_phone
        FROM transactions t 
        LEFT JOIN users u ON t.user_id = u.id 
        WHERE t.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function updateTransactionStatus($id, $status, $admin_note = '') {
    global $pdo;
    
    $stmt = $pdo->prepare("
        UPDATE transactions 
        SET status = ?, admin_note = ?, updated_at = NOW() 
        WHERE id = ?
    ");
    
    return $stmt->execute([$status, $admin_note, $id]);
}

function getTransactionStats() {
    global $pdo;
    
    $stats = [];
    
    // Today's transactions
    $stmt = $pdo->query("SELECT COUNT(*) FROM transactions WHERE DATE(created_at) = CURDATE()");
    $stats['today_transactions'] = $stmt->fetchColumn();
    
    // Failed transactions
    $stmt = $pdo->query("SELECT COUNT(*) FROM transactions WHERE status = 'failed'");
    $stats['failed_transactions'] = $stmt->fetchColumn();
    
    // Pending transactions
    $stmt = $pdo->query("SELECT COUNT(*) FROM transactions WHERE status = 'pending'");
    $stats['pending_transactions'] = $stmt->fetchColumn();
    
    // Total amount today
    $stmt = $pdo->query("SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE DATE(created_at) = CURDATE() AND status = 'completed'");
    $stats['today_amount'] = $stmt->fetchColumn();
    
    return $stats;
}

function exportTransactionsCSV($filters = []) {
    global $pdo;
    
    $where = [];
    $params = [];
    
    if (!empty($filters['date_from'])) {
        $where[] = "DATE(t.created_at) >= ?";
        $params[] = $filters['date_from'];
    }
    
    if (!empty($filters['date_to'])) {
        $where[] = "DATE(t.created_at) <= ?";
        $params[] = $filters['date_to'];
    }
    
    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    
    $query = "SELECT t.id, u.name, u.email, t.service_type, t.amount, t.status, 
                     t.provider, t.reference, t.created_at
              FROM transactions t 
              LEFT JOIN users u ON t.user_id = u.id 
              $whereClause 
              ORDER BY t.created_at DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    
    return $stmt->fetchAll();
}