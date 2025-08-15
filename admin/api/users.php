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

function handleGetRequest($action, $pdo)
{
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

function handlePostRequest($action, $input, $pdo)
{
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

function getUsersList($pdo)
{
    try {
        $query = $_GET['query'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $legacyStatus = $_GET['status'] ?? '';
        $regStatus = $_GET['reg_status'] ?? '';
        $accStatus = $_GET['acc_status'] ?? '';

        // Build WHERE clause
        $whereConditions = [];
        $params = [];

        if (!empty($query)) {
            $whereConditions[] = "(u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR u.phone_number LIKE ? OR u.account_name LIKE ?)";
            $searchTerm = "%$query%";
            // first_name, last_name, email, phone_number, account_name
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // New filters: registration_status and account_status
        if (!empty($regStatus)) {
            $regStatus = strtolower($regStatus);
            if (in_array($regStatus, ['complete', 'incomplete'], true)) {
                $whereConditions[] = 'u.registration_status = ?';
                $params[] = $regStatus;
            }
        }
        if (!empty($accStatus)) {
            $accStatus = strtolower($accStatus);
            $code = mapAccountStatusLabelToCode($accStatus);
            if ($code !== null) {
                $whereConditions[] = 'u.account_status = ?';
                $params[] = $code;
            }
        }
        // Backward compat: legacy single status filter
        if (empty($regStatus) && empty($accStatus) && !empty($legacyStatus)) {
            $legacy = strtolower($legacyStatus);
            if ($legacy === 'pending') {
                $whereConditions[] = 'u.registration_status = ?';
                $params[] = 'incomplete';
            } else {
                $code = mapAccountStatusLabelToCode($legacy);
                if ($code !== null) {
                    $whereConditions[] = 'u.account_status = ?';
                    $params[] = $code;
                }
            }
        }

        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM users u $whereClause";
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $totalCount = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

        // Get users
        $sql = "SELECT 
                    u.user_id AS id,
                    -- Prefer first_name + last_name; fallback to account_name; else email
                    CASE 
                        WHEN TRIM(CONCAT(COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, ''))) <> '' 
                            THEN TRIM(CONCAT(COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')))
                        WHEN COALESCE(u.account_name, '') <> '' THEN u.account_name
                        ELSE u.email
                    END AS full_name,
                    u.email,
                    u.phone_number AS phone,
                    COALESCE(ab.wallet_balance, 0) AS balance,
                    u.kyc_status,
                    u.created_at,
                    NULL AS last_login,
                    u.account_status,
                    u.registration_status
                FROM users u
                LEFT JOIN account_balance ab ON ab.user_id = u.user_id
                $whereClause 
                ORDER BY u.created_at DESC 
                LIMIT $limit OFFSET $offset";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Normalize to UI contract
        $users = [];
        foreach ($rows as $row) {
            $accCode = isset($row['account_status']) ? (int)$row['account_status'] : null;
            $accLabel = mapAccountStatusCodeToLabel($accCode);
            $reg = $row['registration_status'] ?? null;
            $users[] = [
                'id' => (int)$row['id'],
                'full_name' => $row['full_name'] ?? '',
                'email' => $row['email'] ?? '',
                'phone' => $row['phone'] ?? '',
                'balance' => (float)($row['balance'] ?? 0),
                // New explicit fields
                'account_status_code' => $accCode,
                'account_status_label' => $accLabel,
                'registration_status' => $reg,
                'kyc_status' => $row['kyc_status'] ?? 'unverified',
                // Backward field for older UIs (kept but no longer used by new UI)
                'status' => $reg === 'incomplete' ? 'pending' : $accLabel,
                'created_at' => formatDateTime($row['created_at'] ?? null),
                'last_login' => null
            ];
        }

        // Format data
        // (already formatted above)

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

function getUserDetails($pdo)
{
    try {
        $userId = $_GET['id'] ?? '';

        if (empty($userId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            return;
        }
        // Join account_balance for wallet; fetch core fields
        $stmt = $pdo->prepare("SELECT 
                u.user_id AS id,
                u.first_name, u.last_name, u.account_name, u.email, u.phone_number,
                u.account_status, u.registration_status, u.kyc_status, u.created_at,
                COALESCE(ab.wallet_balance, 0) AS balance
            FROM users u
            LEFT JOIN account_balance ab ON ab.user_id = u.user_id
            WHERE u.user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found']);
            return;
        }

        $fullName = trim(implode(' ', array_filter([$user['first_name'] ?? '', $user['last_name'] ?? ''])));
        if ($fullName === '') {
            $fullName = $user['account_name'] ?: $user['email'];
        }

        $accCode = isset($user['account_status']) ? (int)$user['account_status'] : null;
        $data = [
            'id' => (int)$user['id'],
            'full_name' => $fullName,
            'email' => $user['email'] ?? '',
            'phone' => $user['phone_number'] ?? '',
            'balance' => (float)($user['balance'] ?? 0),
            'account_status_code' => $accCode,
            'account_status_label' => mapAccountStatusCodeToLabel($accCode),
            'registration_status' => $user['registration_status'] ?? null,
            'kyc_status' => $user['kyc_status'] ?? 'unverified',
            // Backward
            'status' => ($user['registration_status'] ?? '') === 'incomplete' ? 'pending' : mapAccountStatusCodeToLabel($accCode),
            'created_at' => formatDateTime($user['created_at'] ?? null),
            'last_login' => null
        ];

        echo json_encode(['success' => true, 'data' => $data]);
    } catch (PDOException $e) {
        error_log("User details error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to fetch user details']);
    }
}

function createUser($input, $pdo)
{
    try {
        $fullName = trim($input['full_name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $password = $input['password'] ?? '';
        // New fields
        $inputAccStatus = $input['account_status'] ?? null; // label or code
        $inputRegStatus = strtolower($input['registration_status'] ?? '');
        // Legacy single status fallback
        $legacyStatus = strtolower($input['status'] ?? '');

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
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }

        // Split name into first/last
        $first = $fullName;
        $last = '';
        if (strpos($fullName, ' ') !== false) {
            $parts = preg_split('/\s+/', $fullName, 2);
            $first = $parts[0];
            $last = $parts[1] ?? '';
        }

        // Determine final statuses
        $accountStatus = ACCOUNT_STATUS_ACTIVE; // default 101
        $registrationStatus = 'complete';

        if ($inputAccStatus !== null && $inputAccStatus !== '') {
            // Accept label or numeric-like code
            if (is_numeric($inputAccStatus)) {
                $accountStatus = (int)$inputAccStatus;
            } else {
                $mapped = mapAccountStatusLabelToCode($inputAccStatus);
                if ($mapped !== null) $accountStatus = $mapped;
            }
        }
        if ($inputRegStatus === 'complete' || $inputRegStatus === 'incomplete') {
            $registrationStatus = $inputRegStatus;
        }
        // Legacy fallback
        if ($legacyStatus) {
            if ($legacyStatus === 'pending') $registrationStatus = 'incomplete';
            else {
                $mapped = mapAccountStatusLabelToCode($legacyStatus);
                if ($mapped !== null) $accountStatus = $mapped;
            }
        }

        // Create user (provide safe defaults for NOT NULL columns)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $registrationId = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("INSERT INTO users (
                virtual_account, account_name, bank_name, billstack_ref, user_name,
                first_name, last_name, email, phone_number, password,
                w_bank_name, w_account_number, iuc_number, txn_pin,
                address, state, country, city,
                registration_id, referral_link, referral_code, referred_by,
                registration_status, account_status, failed_attempts,
                kyc_value, kyc_status, kyc_type, created_at, updated_at
            ) VALUES (
                NULL, '', '', '', '',
                ?, ?, ?, ?, ?,
                '', '', '', NULL,
                '', '', '', '',
                ?, '', NULL, NULL,
                ?, ?, 0,
                NULL, 'unverified', NULL, NOW(), NULL
            )");

        $stmt->execute([
            $first,
            $last,
            $email,
            $phone,
            $hashedPassword,
            $registrationId,
            $registrationStatus,
            $accountStatus
        ]);

        $newId = (int)$pdo->lastInsertId();
        // Admin notification
        $nstmt = $pdo->prepare('INSERT INTO admin_notifications (type, title, message, meta, is_read, created_at) VALUES (?,?,?,?,0,NOW())');
        $nstmt->execute([
            'user',
            'User Created',
            "New user $fullName ($email) was created",
            json_encode(['user_id' => $newId]),
        ]);
        // Activity log
        $adminId = (int)($_SESSION['admin_id'] ?? 0);
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $lstmt = $pdo->prepare('INSERT INTO activity_log (username, action_type, action_description, ip_address, created_at) VALUES (?,?,?,?,NOW())');
        $lstmt->execute([
            (string)$adminId,
            'user_create',
            "Created user $fullName ($email)",
            $ip,
        ]);

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

function updateUser($input, $pdo)
{
    try {
        $userId = $input['id'] ?? '';
        $fullName = trim($input['full_name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $inputAccStatus = $input['account_status'] ?? null; // label or code
        $inputRegStatus = strtolower($input['registration_status'] ?? '');
        $legacyStatus = strtolower($input['status'] ?? '');

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
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        $stmt->execute([$email, $userId]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }

        // Split name into first/last
        $first = $fullName;
        $last = '';
        if (strpos($fullName, ' ') !== false) {
            $parts = preg_split('/\s+/', $fullName, 2);
            $first = $parts[0];
            $last = $parts[1] ?? '';
        }

        // Determine statuses respecting provided inputs
        $accountStatus = null; // null means don't change
        $registrationStatus = null; // null means don't change
        if ($inputAccStatus !== null && $inputAccStatus !== '') {
            if (is_numeric($inputAccStatus)) $accountStatus = (int)$inputAccStatus;
            else {
                $mapped = mapAccountStatusLabelToCode($inputAccStatus);
                if ($mapped !== null) $accountStatus = $mapped;
            }
        }
        if ($inputRegStatus === 'complete' || $inputRegStatus === 'incomplete') {
            $registrationStatus = $inputRegStatus;
        }
        // Legacy fallback if neither provided
        if ($accountStatus === null && $registrationStatus === null && $legacyStatus) {
            if ($legacyStatus === 'pending') $registrationStatus = 'incomplete';
            else {
                $mapped = mapAccountStatusLabelToCode($legacyStatus);
                if ($mapped !== null) $accountStatus = $mapped;
            }
        }

        // Build dynamic SQL to avoid overwriting registration_status unless needed
        // Build dynamic parts based on which statuses we modify
        $setParts = [
            'first_name = ?',
            'last_name = ?',
            'email = ?',
            'phone_number = ?',
            'updated_at = NOW()'
        ];
        $values = [$first, $last, $email, $phone];
        if ($accountStatus !== null) {
            array_splice($setParts, 4, 0, 'account_status = ?');
            array_splice($values, 4, 0, $accountStatus);
        }
        if ($registrationStatus !== null) {
            array_splice($setParts, 4 + ($accountStatus !== null ? 1 : 0), 0, 'registration_status = ?');
            array_splice($values, 4 + ($accountStatus !== null ? 1 : 0), 0, $registrationStatus);
        }
        $sql = 'UPDATE users SET ' . implode(', ', $setParts) . ' WHERE user_id = ?';
        $values[] = $userId;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        if ($stmt->rowCount() > 0) {
            // Admin notification
            $nstmt = $pdo->prepare('INSERT INTO admin_notifications (type, title, message, meta, is_read, created_at) VALUES (?,?,?,?,0,NOW())');
            $nstmt->execute([
                'user',
                'User Updated',
                "User $fullName ($email) was updated",
                json_encode(['user_id' => (int)$userId]),
            ]);
            // Activity log
            $adminId = (int)($_SESSION['admin_id'] ?? 0);
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $lstmt = $pdo->prepare('INSERT INTO activity_log (username, action_type, action_description, ip_address, created_at) VALUES (?,?,?,?,NOW())');
            $lstmt->execute([
                (string)$adminId,
                'user_update',
                "Updated user $fullName ($email)",
                $ip,
            ]);
            echo json_encode(['success' => true, 'message' => 'User updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found or no changes made']);
        }
    } catch (PDOException $e) {
        error_log("Update user error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update user']);
    }
}

function toggleUserLock($input, $pdo)
{
    try {
        $userId = $input['id'] ?? '';
        $lock = $input['lock'] ?? false;

        if (empty($userId)) {
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            return;
        }

        $newStatus = $lock ? ACCOUNT_STATUS_LOCKED : ACCOUNT_STATUS_ACTIVE;

        $stmt = $pdo->prepare("UPDATE users SET account_status = ?, updated_at = NOW() WHERE user_id = ?");
        $stmt->execute([$newStatus, $userId]);

        if ($stmt->rowCount() > 0) {
            $action = $lock ? 'locked' : 'unlocked';
            // Admin notification
            $nstmt = $pdo->prepare('INSERT INTO admin_notifications (type, title, message, meta, is_read, created_at) VALUES (?,?,?,?,0,NOW())');
            $nstmt->execute([
                'security',
                'Account Status Changed',
                "User #$userId account was $action",
                json_encode(['user_id' => (int)$userId, 'action' => $action]),
            ]);
            // Activity log
            $adminId = (int)($_SESSION['admin_id'] ?? 0);
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $lstmt = $pdo->prepare('INSERT INTO activity_log (username, action_type, action_description, ip_address, created_at) VALUES (?,?,?,?,NOW())');
            $lstmt->execute([
                (string)$adminId,
                'user_toggle_lock',
                "User #$userId $action",
                $ip,
            ]);
            echo json_encode(['success' => true, 'message' => "User $action successfully"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } catch (PDOException $e) {
        error_log("Toggle user lock error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update user status']);
    }
}

// Helpers
function mapAccountStatusCodeToLabel($accountStatus)
{
    switch ((int)$accountStatus) {
        case ACCOUNT_STATUS_ACTIVE:
            return 'active';
        case ACCOUNT_STATUS_LOCKED:
            return 'locked';
        case ACCOUNT_STATUS_BANNED:
            return 'banned';
        case ACCOUNT_STATUS_INACTIVE:
            return 'inactive';
        default:
            return 'inactive';
    }
}

function mapAccountStatusLabelToCode($label)
{
    $label = strtolower($label);
    switch ($label) {
        case 'active':
            return ACCOUNT_STATUS_ACTIVE;
        case 'locked':
            return ACCOUNT_STATUS_LOCKED;
        case 'banned':
            return ACCOUNT_STATUS_BANNED;
        case 'inactive':
            return ACCOUNT_STATUS_INACTIVE;
        default:
            return null;
    }
}

function formatDateTime($val)
{
    if (!$val) return null;
    $ts = strtotime($val);
    if ($ts === false) return null;
    return date('Y-m-d H:i:s', $ts);
}
