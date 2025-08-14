<?php
session_start();
require_once '../config/config.php';
require_once '../functions/utilities.php';

// Redirect if already logged in as admin
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: /admin/');
    exit;
}

$error_message = '';
$login_attempts = $_SESSION['login_attempts'] ?? 0;
$last_attempt = $_SESSION['last_attempt'] ?? 0;

// Rate limiting: 5 attempts per 15 minutes
if ($login_attempts >= 5 && (time() - $last_attempt) < 900) {
    $error_message = 'Too many failed attempts. Please try again in 15 minutes.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error_message)) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $otp = trim($_POST['otp'] ?? '');
    
    if (empty($email) || empty($password)) {
        $error_message = 'Email and password are required.';
    } else {
        try {
            // Check user credentials
            $stmt = $pdo->prepare("SELECT id, name, email, password, role, status, otp_secret FROM users WHERE email = ? AND role = 'admin'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] !== 'active') {
                    $error_message = 'Account is not active. Please contact support.';
                } else {
                    // If OTP is enabled and required
                    if (!empty($user['otp_secret'])) {
                        if (empty($otp)) {
                            $error_message = 'OTP is required for admin access.';
                        } else {
                            // Verify OTP (simplified - you'd use a proper TOTP library)
                            $expected_otp = generateSimpleOTP($user['otp_secret']);
                            if ($otp !== $expected_otp) {
                                $error_message = 'Invalid OTP code.';
                            }
                        }
                    }
                    
                    if (empty($error_message)) {
                        // Successful login
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_role'] = $user['role'];
                        $_SESSION['login_time'] = time();
                        
                        // Clear login attempts
                        unset($_SESSION['login_attempts']);
                        unset($_SESSION['last_attempt']);
                        
                        // Log successful admin login
                        logAdminActivity($user['id'], 'login', 'admin_session', null, [
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'user_agent' => $_SERVER['HTTP_USER_AGENT']
                        ]);
                        
                        header('Location: /admin/');
                        exit;
                    }
                }
            } else {
                $error_message = 'Invalid email or password.';
            }
            
            // Track failed attempts
            $_SESSION['login_attempts'] = $login_attempts + 1;
            $_SESSION['last_attempt'] = time();
            
        } catch (Exception $e) {
            error_log("Admin login error: " . $e->getMessage());
            $error_message = 'Login failed. Please try again.';
        }
    }
}

// Simple OTP generation (replace with proper TOTP implementation)
function generateSimpleOTP($secret) {
    return substr(md5($secret . date('YmdH')), 0, 6);
}

function logAdminActivity($admin_id, $action, $entity_type, $entity_id, $details = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO audit_logs (admin_id, action, entity_type, entity_id, details, ip_address, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $admin_id,
            $action,
            $entity_type,
            $entity_id,
            json_encode($details),
            $_SERVER['REMOTE_ADDR']
        ]);
    } catch (Exception $e) {
        error_log("Audit log error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Dataspeed</title>
    
    <!-- CSS -->
    <link href="/public/assets/css/soft-ui-dashboard.css" rel="stylesheet" />
    <link href="/public/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/public/assets/css/admin.css" rel="stylesheet" />
    
    <style>
        body {
            background: linear-gradient(310deg, #dc3545 0%, #8B0000 100%);
            min-height: 100vh;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card login-card">
                    <div class="card-header text-center pb-0">
                        <img src="/public/assets/img/logo-ct-dark.png" alt="Dataspeed" class="img-fluid mb-3" style="max-height: 60px;">
                        <h4 class="font-weight-bolder text-dark">Admin Login</h4>
                        <p class="mb-0">Enter your credentials to access the admin panel</p>
                    </div>
                    
                    <div class="card-body">
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                                <span class="alert-text"><?php echo htmlspecialchars($error_message); ?></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" class="admin-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                                       required autocomplete="email" autofocus>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       required autocomplete="current-password">
                            </div>
                            
                            <div class="mb-3">
                                <label for="otp" class="form-label">
                                    OTP Code 
                                    <small class="text-muted">(if enabled)</small>
                                </label>
                                <input type="text" class="form-control" id="otp" name="otp" 
                                       placeholder="Enter 6-digit code" maxlength="6" autocomplete="one-time-code">
                                <small class="form-text text-muted">
                                    Leave blank if OTP is not enabled for your account.
                                </small>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100 my-4 mb-2">
                                    Sign In
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                Forgot your password? Contact system administrator.
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                        <small class="text-muted">
                            © <?php echo date('Y'); ?> Dataspeed. All rights reserved.
                        </small>
                    </div>
                </div>
                
                <?php if ($login_attempts > 0): ?>
                    <div class="text-center mt-3">
                        <small class="text-white-50">
                            Failed attempts: <?php echo $login_attempts; ?>/5
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Core JS Files -->
    <script src="/public/assets/js/core/popper.min.js"></script>
    <script src="/public/assets/js/core/bootstrap.min.js"></script>
    <script src="/public/assets/js/admin.js"></script>
    
    <script>
        // Auto-focus on first empty field
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            
            if (emailField.value) {
                passwordField.focus();
            } else {
                emailField.focus();
            }
            
            // Format OTP input
            const otpField = document.getElementById('otp');
            otpField.addEventListener('input', function(e) {
                // Only allow numbers
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</body>
</html>