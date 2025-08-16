<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/sendMail.php';

$adminId = (int) $_SESSION['admin_id'];
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

try {
    if ($method === 'POST') {
        // Accept JSON bodies
        $input = [];
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $input = json_decode(file_get_contents('php://input'), true) ?: [];
        } else {
            $input = $_POST;
        }
        $action = $input['action'] ?? $action;
        switch ($action) {
            case 'create':
                if (!isSuperAdmin($pdo, $adminId)) {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Forbidden']);
                    break;
                }
                echo json_encode(createAdmin($pdo, $input));
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
    error_log('admin.php API error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

function isSuperAdmin(PDO $pdo, int $adminId): bool
{
    try {
        $stmt = $pdo->prepare('SELECT role FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$adminId]);
        $role = (string)$stmt->fetchColumn();
        if ($role === '' || $role === null) {
            // Fallback: treat the very first admin as super
            return $adminId === 1;
        }
        $norm = strtolower(preg_replace('/[^a-z]/i', '', $role)); // normalize e.g., "Super Admin" => "superadmin"
        return in_array($norm, ['super', 'superadmin'], true);
    } catch (Throwable $e) {
        error_log('isSuperAdmin: ' . $e->getMessage());
        return false;
    }
}

function createAdmin(PDO $pdo, array $input): array
{
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $role = strtolower(trim($input['role'] ?? 'manager'));
    $status = strtolower(trim($input['status'] ?? 'active'));

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Valid name and email are required'];
    }
    $allowedRoles = ['super', 'manager', 'support'];
    if (!in_array($role, $allowedRoles, true)) {
        $role = 'manager';
    }
    $allowedStatus = ['active', 'inactive'];
    if (!in_array($status, $allowedStatus, true)) {
        $status = 'active';
    }

    // Ensure unique email
    $chk = $pdo->prepare('SELECT 1 FROM admins WHERE email = ? LIMIT 1');
    $chk->execute([$email]);
    if ($chk->fetchColumn()) {
        return ['success' => false, 'message' => 'Email already exists'];
    }

    $hash = password_hash('Pa$$w0rd!', PASSWORD_BCRYPT);
    // Generate a non-default random passphrase for secondary verification
    $rawPassphrase = bin2hex(random_bytes(8)); // 16 hex chars (~64 bits)
    $passphraseHash = password_hash($rawPassphrase, PASSWORD_BCRYPT);

    // Insert using available columns only
    $cols = getTableColumns($pdo, 'admins');
    $insertCols = [];
    $placeholders = [];
    $params = [];
    if (in_array('name', $cols, true)) {
        $insertCols[] = 'name';
        $placeholders[] = '?';
        $params[] = $name;
    }
    if (in_array('email', $cols, true)) {
        $insertCols[] = 'email';
        $placeholders[] = '?';
        $params[] = $email;
    }
    if (in_array('password', $cols, true)) {
        $insertCols[] = 'password';
        $placeholders[] = '?';
        $params[] = $hash;
    }
    if (in_array('role', $cols, true)) {
        $insertCols[] = 'role';
        $placeholders[] = '?';
        $params[] = $role;
    }
    if (in_array('status', $cols, true)) {
        $insertCols[] = 'status';
        $placeholders[] = '?';
        $params[] = $status;
    }
    if (in_array('passphrase', $cols, true)) {
        $insertCols[] = 'passphrase';
        $placeholders[] = '?';
        $params[] = $passphraseHash;
    }
    if (in_array('created_at', $cols, true)) {
        $insertCols[] = 'created_at';
        $placeholders[] = 'NOW()';
    }

    if (empty($insertCols)) {
        return ['success' => false, 'message' => 'Admins table is missing required columns'];
    }

    $sql = 'INSERT INTO admins (' . implode(',', $insertCols) . ') VALUES (' . implode(',', $placeholders) . ')';

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $newId = (int)$pdo->lastInsertId();

        // Send verification/welcome email
        $subject = 'Your DataSpeed Admin Account';
        $body = '<p>Hello ' . htmlspecialchars($name) . ',</p>'
            . '<p>Your admin account has been created.</p>'
            . '<p><strong>Email:</strong> ' . htmlspecialchars($email) . '<br>'
            . '<strong>Default Password:</strong> Pa$$w0rd!<br>'
            . '<strong>Passphrase:</strong> ' . htmlspecialchars($rawPassphrase) . '</p>'
            . '<p>Please sign in to the admin portal and keep your credentials secure.</p>'
            . '<p>Regards,<br>DataSpeed Team</p>';
        $mailOk = sendMail($email, $subject, $body);

        $pdo->commit();
        $msg = $mailOk ? 'Admin created and email sent' : 'Admin created but email failed';
        return ['success' => true, 'message' => $msg, 'admin_id' => $newId];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('createAdmin: ' . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to create admin'];
    }
}

function getTableColumns(PDO $pdo, string $table): array
{
    try {
        $stmt = $pdo->query('DESCRIBE ' . $table);
        $cols = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $cols[] = $row['Field'];
        }
        return $cols;
    } catch (Throwable $e) {
        error_log('getTableColumns error: ' . $e->getMessage());
        return [];
    }
}
