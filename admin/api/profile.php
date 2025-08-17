<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require __DIR__ . '/../../config/config.php';

$adminId = (int) $_SESSION['admin_id'];
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

try {
    if ($method === 'GET') {
        switch ($action) {
            case 'get':
                echo json_encode(['success' => true, 'data' => getProfile($pdo, $adminId)]);
                break;
            case 'stats':
                echo json_encode(['success' => true, 'data' => getStats($pdo)]);
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } elseif ($method === 'POST') {
        // For JSON bodies
        $input = [];
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $input = json_decode(file_get_contents('php://input'), true) ?: [];
        } else {
            $input = $_POST;
        }

        $action = $input['action'] ?? ($_GET['action'] ?? $action);
        switch ($action) {
            case 'updatePersonal':
                echo json_encode(updatePersonal($pdo, $adminId, $input));
                break;
            case 'changePassword':
                echo json_encode(changePassword($pdo, $adminId, $input));
                break;
            case 'updatePreferences':
                echo json_encode(updatePreferences($pdo, $adminId, $input));
                break;
            case 'uploadAvatar':
                echo json_encode(uploadAvatar($pdo, $adminId));
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Throwable $e) {
    error_log('profile.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

function getProfile(PDO $pdo, int $adminId): array
{
    // Detect available columns to avoid errors on schema differences
    $cols = getTableColumns($pdo, 'admins');
    $want = ['id', 'name', 'email', 'role', 'status', 'last_login_at', 'created_at'];
    $selectCols = array_values(array_intersect($want, $cols));
    if (empty($selectCols)) {
        $selectCols = ['id'];
    }
    $sql = 'SELECT ' . implode(',', $selectCols) . ' FROM admins WHERE id = ? LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$adminId]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    if (!$admin) return [];

    // Derive first/last name from name
    $first = $admin['name'] ?? '';
    $last = '';
    if (!empty($admin['name']) && strpos($admin['name'], ' ') !== false) {
        [$first, $last] = explode(' ', $admin['name'], 2);
    }

    // Fetch per-admin preferences from settings
    $keys = [
        "admin_{$adminId}_email_notifications",
        "admin_{$adminId}_transaction_alerts",
        "admin_{$adminId}_theme",
        "admin_{$adminId}_language",
        "admin_{$adminId}_two_factor_enabled",
        "admin_{$adminId}_username",
        "admin_{$adminId}_phone",
        "admin_{$adminId}_avatar_url",
    ];
    $placeholders = implode(',', array_fill(0, count($keys), '?'));
    $prefStmt = $pdo->prepare("SELECT `key`, `value` FROM settings WHERE `key` IN ($placeholders)");
    $prefStmt->execute($keys);
    $prefs = [];
    foreach ($prefStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $prefs[$row['key']] = $row['value'];
    }

    return [
        'id' => (int)$admin['id'],
        'first_name' => $first,
        'last_name' => $last,
        'username' => $prefs["admin_{$adminId}_username"] ?? '',
        'email' => $admin['email'] ?? '',
        'phone' => $prefs["admin_{$adminId}_phone"] ?? '',
        'role' => $admin['role'] ?? '',
        'avatar_url' => $prefs["admin_{$adminId}_avatar_url"] ?? '../public/favicon.png',
        'last_login' => $admin['last_login_at'] ?? null,
        'created_at' => $admin['created_at'] ?? null,
        'email_notifications' => (int)($prefs["admin_{$adminId}_email_notifications"] ?? 1),
        'transaction_alerts' => (int)($prefs["admin_{$adminId}_transaction_alerts"] ?? 1),
        'theme' => $prefs["admin_{$adminId}_theme"] ?? 'light',
        'language' => $prefs["admin_{$adminId}_language"] ?? 'en',
        'two_factor_enabled' => (int)($prefs["admin_{$adminId}_two_factor_enabled"] ?? 0),
    ];
}

function getStats(PDO $pdo): array
{
    $users = (int) ($pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?: 0);
    $txs = (int) ($pdo->query("SELECT COUNT(*) FROM transactions")->fetchColumn() ?: 0);
    return [
        'total_users' => $users,
        'total_transactions' => $txs,
    ];
}

function updatePersonal(PDO $pdo, int $adminId, array $input): array
{
    $first = trim($input['first_name'] ?? '');
    $last = trim($input['last_name'] ?? '');
    $username = trim($input['username'] ?? '');
    $email = trim($input['email'] ?? '');
    $phone = trim($input['phone'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email'];
    }

    $pdo->beginTransaction();
    try {
        $name = trim($first . ' ' . $last);
        // Build UPDATE for available columns only
        $cols = getTableColumns($pdo, 'admins');
        $set = [];
        $params = [];
        if (in_array('name', $cols, true)) {
            $set[] = 'name = ?';
            $params[] = $name;
        }
        if (in_array('email', $cols, true)) {
            $set[] = 'email = ?';
            $params[] = $email;
        }
        if (!empty($set)) {
            $sql = 'UPDATE admins SET ' . implode(', ', $set) . ' WHERE id = ?';
            $params[] = $adminId;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }

        upsertSettings($pdo, [
            "admin_{$adminId}_username" => $username,
            "admin_{$adminId}_phone" => $phone,
        ]);

        // Update session mirrors if present
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_first_name'] = $first;
        $_SESSION['admin_last_name'] = $last;
        $_SESSION['admin_phone'] = $phone;

        $pdo->commit();
        return ['success' => true, 'message' => 'Profile updated'];
    } catch (Throwable $e) {
        $pdo->rollBack();
        error_log('updatePersonal: ' . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to update profile'];
    }
}

function changePassword(PDO $pdo, int $adminId, array $input): array
{
    $current = (string)($input['current_password'] ?? '');
    $new = (string)($input['new_password'] ?? '');
    $confirm = (string)($input['confirm_password'] ?? '');

    if (strlen($new) < 8) {
        return ['success' => false, 'message' => 'Password too short'];
    }
    if ($new !== $confirm) {
        return ['success' => false, 'message' => 'Passwords do not match'];
    }

    $stmt = $pdo->prepare("SELECT password FROM admins WHERE id = ?");
    $stmt->execute([$adminId]);
    $hash = $stmt->fetchColumn();
    if (!$hash || !password_verify($current, $hash)) {
        return ['success' => false, 'message' => 'Current password is incorrect'];
    }

    $newHash = password_hash($new, PASSWORD_BCRYPT);
    $upd = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
    $upd->execute([$newHash, $adminId]);

    return ['success' => true, 'message' => 'Password updated'];
}

function updatePreferences(PDO $pdo, int $adminId, array $input): array
{
    $pairs = [
        "admin_{$adminId}_email_notifications" => (string)(isset($input['email_notifications']) && (int)$input['email_notifications'] ? '1' : '0'),
        "admin_{$adminId}_transaction_alerts" => (string)(isset($input['transaction_alerts']) && (int)$input['transaction_alerts'] ? '1' : '0'),
        "admin_{$adminId}_theme" => (string)($input['theme'] ?? 'light'),
        "admin_{$adminId}_language" => (string)($input['language'] ?? 'en'),
        "admin_{$adminId}_two_factor_enabled" => (string)(isset($input['two_factor_enabled']) && (int)$input['two_factor_enabled'] ? '1' : '0'),
    ];

    try {
        upsertSettings($pdo, $pairs);
        return ['success' => true, 'message' => 'Preferences updated'];
    } catch (Throwable $e) {
        error_log('updatePreferences: ' . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to save preferences'];
    }
}

function uploadAvatar(PDO $pdo, int $adminId): array
{
    if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No file uploaded'];
    }
    $file = $_FILES['avatar'];
    if ($file['size'] > 2 * 1024 * 1024) { // 2MB
        return ['success' => false, 'message' => 'File too large (max 2MB)'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/heic' => 'heic',
    ];
    if (!isset($allowed[$mime])) {
        return ['success' => false, 'message' => 'Unsupported file type'];
    }

    $ext = $allowed[$mime];
    $safeName = 'admin_' . $adminId . '_' . time() . '.' . $ext;
    $targetDir = realpath(__DIR__ . '/../../public/assets/img') . DIRECTORY_SEPARATOR . 'admin-avatars';
    if (!is_dir($targetDir)) {
        @mkdir($targetDir, 0775, true);
    }
    $targetPath = $targetDir . DIRECTORY_SEPARATOR . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => false, 'message' => 'Failed to save file'];
    }

    // Path to use from admin pages (relative)
    $relative = '../public/assets/img/admin-avatars/' . $safeName;
    upsertSettings($pdo, ["admin_{$adminId}_avatar_url" => $relative]);

    return ['success' => true, 'avatar_url' => $relative];
}

function upsertSettings(PDO $pdo, array $pairs): void
{
    if (empty($pairs)) return;
    // Only start/commit a transaction if one isn't already active
    $ownTx = !$pdo->inTransaction();
    if ($ownTx) {
        $pdo->beginTransaction();
    }
    try {
        $stmt = $pdo->prepare("INSERT INTO settings (`key`,`value`, `updated_at`) VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`)");
        foreach ($pairs as $k => $v) {
            // basic key guard
            if (!preg_match('/^[a-zA-Z0-9_]+$/', str_replace(['-', ':'], '_', $k))) {
                continue;
            }
            $stmt->execute([$k, (string)$v]);
        }
        if ($ownTx) {
            $pdo->commit();
        }
    } catch (Throwable $e) {
        if ($ownTx && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

/**
 * Return lowercased column names for a table (cached per request).
 */
function getTableColumns(PDO $pdo, string $table): array
{
    static $cache = [];
    $key = strtolower($table);
    if (isset($cache[$key])) return $cache[$key];
    try {
        $stmt = $pdo->query('DESCRIBE ' . $table);
        $cols = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $cols[] = $row['Field'];
        }
        return $cache[$key] = $cols;
    } catch (Throwable $e) {
        error_log('getTableColumns error: ' . $e->getMessage());
        return $cache[$key] = [];
    }
}
