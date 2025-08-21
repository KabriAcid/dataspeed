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
    <link rel="shortcut icon" href="../public/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Toasted CSS/JS for notifications -->
    <link rel="stylesheet" href="../public/assets/css/toasted.css">
    <script src="../public/assets/js/toasted.js"></script>

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
                        <input type="email" class="form-control with-icon" id="email" name="email" placeholder="Enter your email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address" autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="form-field has-icon has-toggle">
                        <i class="input-icon ni ni-lock-circle-open" aria-hidden="true"></i>
                        <input type="password" class="form-control with-icon with-toggle" id="password" name="password" placeholder="Enter your password" pattern=".{6,}" title="Please enter at least 6 characters" autocomplete="current-password">
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

                <p class="text-secondary text-sm text-center mt-3"><a class="text-secondary" href="../index.php">Back to homepage</a></p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin.js"></script>
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer"></div>
    <!-- Passphrase Modal -->
    <div class="modal fade" id="passphraseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enter Passphrase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="adminPassphrase">Security Passphrase</label>
                        <input type="password" id="adminPassphrase" class="form-control" placeholder="Enter your passphrase" autocomplete="one-time-code">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="passphraseSubmit">Verify</button>
                </div>
            </div>
        </div>
    </div>
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
                        icon.classList.toggle('fa-eye', !isPassword);
                        icon.classList.toggle('fa-eye-slash', isPassword);
                    }
                    toggleBtn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                    toggleBtn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
                });
            }

            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                // Client-side validation with toasts
                const emailInput = document.getElementById('email');
                if (!emailInput.checkValidity()) {
                    showToasted('Please enter a valid email address.', 'error');
                    emailInput.focus();
                    return;
                }
                if (!password || password.length < 6) {
                    showToasted('Password must be at least 6 characters.', 'error');
                    pwdInput.focus();
                    return;
                }

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

                    if (loginResponse.success && loginResponse.step === 'passphrase') {
                        // Show passphrase modal
                        const modal = new bootstrap.Modal(document.getElementById('passphraseModal'));
                        modal.show();
                        const input = document.getElementById('adminPassphrase');
                        const submitBtn = document.getElementById('passphraseSubmit');
                        const submitHandler = async () => {
                            const passphrase = input.value.trim();
                            if (!passphrase) {
                                showToasted('Please enter your passphrase.', 'error');
                                input.focus();
                                return;
                            }
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';
                            try {
                                const verifyRes = await apiFetch('api/auth.php', {
                                    method: 'POST',
                                    body: JSON.stringify({
                                        action: 'verify_passphrase',
                                        passphrase
                                    })
                                });
                                if (verifyRes.success) {
                                    showToasted('Login successful! Redirecting...', 'success');
                                    setTimeout(() => {
                                        window.location.href = verifyRes.redirect;
                                    }, 500);
                                } else {
                                    showToasted(verifyRes.message || 'Invalid passphrase', 'error');
                                }
                            } catch (err) {
                                showToasted('Verification failed. Try again.', 'error');
                            } finally {
                                submitBtn.disabled = false;
                                submitBtn.textContent = 'Verify';
                            }
                        };
                        submitBtn.onclick = submitHandler;
                        input.onkeydown = (e) => {
                            if (e.key === 'Enter') submitHandler();
                        };
                        input.focus();
                    } else if (loginResponse.success) {
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