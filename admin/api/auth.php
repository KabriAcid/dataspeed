<?php
session_start();
header('Content-Type: application/json');

require __DIR__ . '/../../config/config.php';

// Get request data
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch ($action) {
    case 'validate':
        validateCredentials($input);
        break;
    case 'login':
        authenticateAdmin($input, $pdo);
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
        // Check if admin exists
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

        // Regenerate session ID for security
        session_regenerate_id(true);

        // Set session
        $_SESSION['admin_id'] = $admin['id'];

        // Update last login
        $stmt = $pdo->prepare("UPDATE admins SET last_login_at = NOW() WHERE id = ?");
        $stmt->execute([$admin['id']]);

        echo json_encode([
            'success' => true,
            'redirect' => 'dashboard.php'
        ]);
    } catch (PDOException $e) {
        error_log("Admin login error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Login failed. Please try again.'
        ]);
    }
}
