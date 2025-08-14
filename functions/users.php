<?php
require_once '../config/config.php';

function getUsers($page = 1, $limit = 20, $search = '', $status = '', $role = '') {
    global $pdo;
    
    $offset = ($page - 1) * $limit;
    $where = [];
    $params = [];
    
    if ($search) {
        $where[] = "(name LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    if ($status) {
        $where[] = "status = ?";
        $params[] = $status;
    }
    
    if ($role) {
        $where[] = "role = ?";
        $params[] = $role;
    }
    
    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    
    // Get total count
    $countQuery = "SELECT COUNT(*) FROM users $whereClause";
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();
    
    // Get users
    $query = "SELECT id, name, email, phone, role, status, kyc_status, balance, created_at 
              FROM users $whereClause 
              ORDER BY created_at DESC 
              LIMIT $limit OFFSET $offset";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $users = $stmt->fetchAll();
    
    return [
        'users' => $users,
        'total' => $total,
        'pages' => ceil($total / $limit),
        'current_page' => $page
    ];
}

function getUserById($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createUser($data) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, phone, role, status, pin) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $pin = $data['pin'] ?? generatePin();
    
    return $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['role'] ?? 'user',
        $data['status'] ?? 'active',
        password_hash($pin, PASSWORD_DEFAULT)
    ]);
}

function updateUser($id, $data) {
    global $pdo;
    
    $fields = ['name', 'email', 'phone', 'role', 'status'];
    $updates = [];
    $params = [];
    
    foreach ($fields as $field) {
        if (isset($data[$field])) {
            $updates[] = "$field = ?";
            $params[] = $data[$field];
        }
    }
    
    if (empty($updates)) return false;
    
    $params[] = $id;
    $query = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
    
    $stmt = $pdo->prepare($query);
    return $stmt->execute($params);
}

function generatePin() {
    return str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
}

function getUserStats() {
    global $pdo;
    
    $stats = [];
    
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $stats['total_users'] = $stmt->fetchColumn();
    
    // Active users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'active'");
    $stats['active_users'] = $stmt->fetchColumn();
    
    // KYC pending
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE kyc_status = 'pending'");
    $stats['kyc_pending'] = $stmt->fetchColumn();
    
    return $stats;
}