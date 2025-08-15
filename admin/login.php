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
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />

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
                    <div class="form-field has-icon">
                        <i class="input-icon ni ni-email-83" aria-hidden="true"></i>
                        <input type="email" class="form-control with-icon" id="email" name="email" required placeholder="Enter your email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address" autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="form-field has-icon has-toggle">
                        <i class="input-icon ni ni-lock-circle-open" aria-hidden="true"></i>
                        <input type="password" class="form-control with-icon with-toggle" id="password" name="password" required placeholder="Enter your password" pattern=".{6,}" title="Please enter at least 6 characters" autocomplete="current-password">
                        <button type="button" class="toggle-icon" id="togglePassword" aria-label="Show password" title="Show password">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
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
            const pwdInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');

            // Toggle password visibility
            if (toggleBtn && pwdInput) {
                toggleBtn.addEventListener('click', function() {
                    const isPassword = pwdInput.getAttribute('type') === 'password';
                    pwdInput.setAttribute('type', isPassword ? 'text' : 'password');
                    const icon = toggleBtn.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('ni-eye-17', !isPassword);
                        icon.classList.toggle('ni-fat-remove', isPassword);
                    }
                    toggleBtn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                    toggleBtn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
                });
            }

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