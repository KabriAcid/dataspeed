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

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET') {
    handleGetRequest($action, $pdo);
} else if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    handlePostRequest($action, $input, $pdo);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}

function handleGetRequest($action, $pdo) {
    switch ($action) {
        case 'list':
            getUsersList($pdo);
            break;
        case 'view':
            getUserDetails($pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function handlePostRequest($action, $input, $pdo) {
    switch ($action) {
        case 'create':
            createUser($input, $pdo);
            break;
        case 'update':
            updateUser($input, $pdo);
            break;
        case 'toggleLock':
            toggleUserLock($input, $pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function getUsersList($pdo) {
    try {
        $query = $_GET['query'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $status = $_GET['status'] ?? '';
        
        // Build WHERE clause
        $whereConditions = [];
        $params = [];
        
        if (!empty($query)) {
            $whereConditions[] = "(full_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $searchTerm = "%$query%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($status)) {
            $whereConditions[] = "status = ?";
            $params[] = $status;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM users $whereClause";
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $totalCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Get users
        $sql = "SELECT id, full_name, email, phone, balance, status, kyc_status, created_at, last_login 
                FROM users $whereClause 
                ORDER BY created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format data
        foreach ($users as &$user) {
            $user['balance'] = (float)$user['balance'];
            $user['created_at'] = date('Y-m-d H:i:s', strtotime($user['created_at']));
            $user['last_login'] = $user['last_login'] ? date('Y-m-d H:i:s', strtotime($user['last_login'])) : null;
        }
        
        $totalPages = ceil($totalCount / $limit);
        
        echo json_encode([
            'success' => true,
            'data' => [
                'items' => $users,
                'pagination' => [
                    'page' => $page,
                    'total_pages' => $totalPages,
                    'count' => $totalCount,
                    'per_page' => $limit
                ]
            ]
        ]);
        
    } catch (PDOException $e) {
        error_log("Users list error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to fetch users']);
    }
}

function getUserDetails($pdo) {
    try {
        $userId = $_GET['id'] ?? '';
        
        if (empty($userId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            return;
        }
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found']);
            return;
        }
        
        // Format data
        $user['balance'] = (float)$user['balance'];
        $user['created_at'] = date('Y-m-d H:i:s', strtotime($user['created_at']));
        $user['last_login'] = $user['last_login'] ? date('Y-m-d H:i:s', strtotime($user['last_login'])) : null;
        
        echo json_encode([
            'success' => true,
            'data' => $user
        ]);
        
    } catch (PDOException $e) {
        error_log("User details error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to fetch user details']);
    }
}

function createUser($input, $pdo) {
    try {
        $fullName = trim($input['full_name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $password = $input['password'] ?? '';
        $status = $input['status'] ?? 'active';
        
        // Validate input
        $errors = [];
        
        if (empty($fullName)) {
            $errors[] = 'Full name is required';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }
        
        if (empty($phone)) {
            $errors[] = 'Phone number is required';
        }
        
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }
        
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
            return;
        }
        
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }
        
        // Create user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (full_name, email, phone, password, status, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([$fullName, $email, $phone, $hashedPassword, $status]);
        
        echo json_encode(['success' => true, 'message' => 'User created successfully']);
        
    } catch (PDOException $e) {
        error_log("Create user error: " . $e->getMessage());
        
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'Email or phone already exists']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create user']);
        }
    }
}

function updateUser($input, $pdo) {
    try {
        $userId = $input['id'] ?? '';
        $fullName = trim($input['full_name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $status = $input['status'] ?? 'active';
        
        if (empty($userId)) {
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            return;
        }
        
        // Validate input
        $errors = [];
        
        if (empty($fullName)) {
            $errors[] = 'Full name is required';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }
        
        if (empty($phone)) {
            $errors[] = 'Phone number is required';
        }
        
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
            return;
        }
        
        // Check if email exists for other users
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $userId]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }
        
        // Update user
        $stmt = $pdo->prepare("
            UPDATE users 
            SET full_name = ?, email = ?, phone = ?, status = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        $stmt->execute([$fullName, $email, $phone, $status, $userId]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'User updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found or no changes made']);
        }
        
    } catch (PDOException $e) {
        error_log("Update user error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update user']);
    }
}

function toggleUserLock($input, $pdo) {
    try {
        $userId = $input['id'] ?? '';
        $lock = $input['lock'] ?? false;
        
        if (empty($userId)) {
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            return;
        }
        
        $newStatus = $lock ? 'locked' : 'active';
        
        $stmt = $pdo->prepare("UPDATE users SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$newStatus, $userId]);
        
        if ($stmt->rowCount() > 0) {
            $action = $lock ? 'locked' : 'unlocked';
            echo json_encode(['success' => true, 'message' => "User $action successfully"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
        
    } catch (PDOException $e) {
        error_log("Toggle user lock error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update user status']);
    }
}
?>