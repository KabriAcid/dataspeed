<?php
session_start();
header('Content-Type: application/json');

require __DIR__ . '/../../config/config.php';

// Get request data
$inputRaw = file_get_contents('php://input');
$input = json_decode($inputRaw, true);
$action = $input['action'] ?? ($_POST['action'] ?? ($_GET['action'] ?? ''));

switch ($action) {
    case 'validate':
        validateCredentials($input);
        break;
    case 'login':
        authenticateAdmin($input, $pdo);
        break;
    case 'verify_passphrase':
        verifyPassphrase($input, $pdo);
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function validateCredentials($input)
{
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    $errors = [];

    // Validate email
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    // Validate password
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }

    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
    } else {
        echo json_encode(['success' => true]);
    }
}

function authenticateAdmin($input, $pdo)
{
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    try {
        // Check if admin exists (avoid selecting optional columns directly)
        $stmt = $pdo->prepare("SELECT id, email, password, status FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid email or password'
            ]);
            return;
        }

        // Verify password
        if (!password_verify($password, $admin['password'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid email or password'
            ]);
            return;
        }

        // Check if admin is active
        if ($admin['status'] !== 'active') {
            echo json_encode([
                'success' => false,
                'message' => 'Your account has been suspended. Please contact support.'
            ]);
            return;
        }

        // If passphrase column exists and value is set, require passphrase step
        $requiresPass = false;
        if (columnExists($pdo, 'admins', 'passphrase')) {
            $stmt = $pdo->prepare("SELECT passphrase FROM admins WHERE id = ? LIMIT 1");
            $stmt->execute([$admin['id']]);
            $hash = (string)$stmt->fetchColumn();
            $requiresPass = !empty($hash);
        }

        // Regenerate session ID for security
        session_regenerate_id(true);

        if ($requiresPass) {
            // Set pending admin session; do not grant full access yet
            $_SESSION['pending_admin_id'] = (int)$admin['id'];
            echo json_encode([
                'success' => true,
                'step' => 'passphrase',
                'message' => 'Enter your passphrase to complete login.'
            ]);
            return;
        }

        // Fallback: no passphrase required, complete login
        $_SESSION['admin_id'] = $admin['id'];
        $stmt = $pdo->prepare("UPDATE admins SET last_login_at = NOW() WHERE id = ?");
        $stmt->execute([$admin['id']]);
        echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
    } catch (PDOException $e) {
        error_log("Admin login error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Login failed. Please try again.'
        ]);
    }
}

function verifyPassphrase($input, $pdo)
{
    $phrase = trim($input['passphrase'] ?? '');
    $pendingId = (int)($_SESSION['pending_admin_id'] ?? 0);
    if ($pendingId <= 0) {
        echo json_encode(['success' => false, 'message' => 'No pending login session']);
        return;
    }
    if ($phrase === '') {
        echo json_encode(['success' => false, 'message' => 'Passphrase is required']);
        return;
    }
    try {
        if (!columnExists($pdo, 'admins', 'passphrase')) {
            echo json_encode(['success' => false, 'message' => 'Passphrase not configured']);
            return;
        }
        $stmt = $pdo->prepare("SELECT passphrase FROM admins WHERE id = ? LIMIT 1");
        $stmt->execute([$pendingId]);
        $hash = (string)$stmt->fetchColumn();
        if (!$hash) {
            echo json_encode(['success' => false, 'message' => 'Passphrase not set']);
            return;
        }
        if (!password_verify($phrase, $hash)) {
            echo json_encode(['success' => false, 'message' => 'Invalid passphrase']);
            return;
        }
        // Success: promote pending to full session
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $pendingId;
        unset($_SESSION['pending_admin_id']);
        // Update last login
        $stmt = $pdo->prepare("UPDATE admins SET last_login_at = NOW() WHERE id = ?");
        $stmt->execute([$_SESSION['admin_id']]);
        echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
    } catch (Throwable $e) {
        error_log('verifyPassphrase error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Verification failed']);
    }
}

function columnExists(PDO $pdo, string $table, string $column): bool
{
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $stmt->execute([$table, $column]);
        return (bool)$stmt->fetchColumn();
    } catch (Throwable $e) {
        return false;
    }
}
