<?php
session_start();

// Redirect if already logged in
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DataSpeed VTU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/nucleo@1.0.0/css/nucleo.css" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <img src="../public/favicon.png" alt="" class="img-fluid">
                </div>
                <h2>Admin Login</h2>
                <p class="text-muted">Sign in to your admin dashboard</p>
            </div>
            
            <form id="loginForm" class="login-form">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ni ni-email-83"></i>
                        </span>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ni ni-lock-circle-open"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login" id="loginBtn">
                    <span class="btn-text">Sign In</span>
                    <span class="btn-spinner d-none">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                    </span>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const btnText = loginBtn.querySelector('.btn-text');
            const btnSpinner = loginBtn.querySelector('.btn-spinner');
            
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                // Show loading state
                loginBtn.disabled = true;
                btnText.classList.add('d-none');
                btnSpinner.classList.remove('d-none');
                
                try {
                    // First validate inputs
                    const validateResponse = await apiFetch('api/auth.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            action: 'validate',
                            email: email,
                            password: password
                        })
                    });
                    
                    if (!validateResponse.success) {
                        showToasted(validateResponse.message, 'error');
                        return;
                    }
                    
                    // Then attempt login
                    const loginResponse = await apiFetch('api/auth.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            action: 'login',
                            email: email,
                            password: password
                        })
                    });
                    
                    if (loginResponse.success) {
                        showToasted('Login successful! Redirecting...', 'success');
                        setTimeout(() => {
                            window.location.href = loginResponse.redirect;
                        }, 1000);
                    } else {
                        showToasted(loginResponse.message, 'error');
                    }
                    
                } catch (error) {
                    showToasted('Login failed. Please try again.', 'error');
                    console.log(error);
                } finally {
                    // Reset button state
                    loginBtn.disabled = false;
                    btnText.classList.remove('d-none');
                    btnSpinner.classList.add('d-none');
                }
            });
        });
    </script>
</body>
</html>