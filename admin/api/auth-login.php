<?php
// Admin Authentication API
session_start();
header('Content-Type: application/json');

require_once '../../config/config.php';
require_once '../../functions/utilities.php';

// Early rate limit using session counters (simple server-side gate)
$login_attempts = $_SESSION['login_attempts'] ?? 0;
$last_attempt = $_SESSION['last_attempt'] ?? 0;
if ($login_attempts >= 5 && (time() - $last_attempt) < 900) {
    echo json_encode([
        'success' => false,
        'message' => 'Too many failed attempts. Try again later.',
    ]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$otp = trim($_POST['otp'] ?? '');

if ($email === '' || $password === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Email and password are required.',
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, name, email, password, role, status, otp_secret FROM admins WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin || !password_verify($password, $admin['password'])) {
        $_SESSION['login_attempts'] = $login_attempts + 1;
        $_SESSION['last_attempt'] = time();
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password.',
        ]);
        exit;
    }

    if ($admin['status'] !== 'active') {
        echo json_encode([
            'success' => false,
            'message' => 'Account is not active. Please contact support.',
        ]);
        exit;
    }

    // OTP check if enabled
    if (!empty($admin['otp_secret'])) {
        if ($otp === '') {
            echo json_encode([
                'success' => false,
                'message' => 'OTP is required for admin access.',
                'otp_required' => true,
            ]);
            exit;
        }
        $expected = generateSimpleOTP($admin['otp_secret']);
        if ($otp !== $expected) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid OTP code.',
                'otp_required' => true,
            ]);
            exit;
        }
    }

    // Success: store only admin_id in session
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['login_time'] = time();

    unset($_SESSION['login_attempts']);
    unset($_SESSION['last_attempt']);

    // Log successful admin login
    logAdminActivity($admin['id'], 'login', 'admin_session', null, [
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Login successful.',
        'redirect' => 'dashboard.php',
    ]);
} catch (Throwable $e) {
    error_log('Admin auth-login error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Login failed. Please try again.',
    ]);
}

// Simple OTP generation (replace with proper TOTP later)
function generateSimpleOTP($secret)
{
    return substr(md5($secret . date('YmdH')), 0, 6);
}

function logAdminActivity($admin_id, $action, $entity_type, $entity_id, $details = [])
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, details, ip_address, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $admin_id,
            $action,
            $entity_type,
            $entity_id,
            json_encode($details),
            $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    } catch (Throwable $e) {
        error_log('Audit log error: ' . $e->getMessage());
    }
}
