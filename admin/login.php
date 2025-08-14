<?php
session_start();
// If already logged in, redirect
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Dataspeed</title>

    <!-- CSS -->
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />

    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <!-- <script src="../assets/js/lottie-player.js"></script> -->

    <!-- Toasted JS for notifications -->
    <link rel="stylesheet" href="../public/assets/css/toasted.css" />
    <script src="../public/assets/js/toasted.js"></script>

    <link rel="stylesheet" href="../public/assets/css/soft-design-system-pro.min3f71.css">

    <link href="../public/assets/css/style.css" rel="stylesheet" />
    <link href="../public/assets/css/admin.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card login-card">
                    <div class="card-header text-center pb-0">
                        <img src="../public/favicon.png" alt="Dataspeed" class="img-fluid mb-3" style="height:50px; width:50px; max-height: 60px;">
                    </div>

                    <div class="card-body">
                        <form method="POST" class="admin-form" onsubmit="return false;" id="admin-login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placheholder="Enter your email"
                                    required autocomplete="email" autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password"
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
                                <button type="submit" id="login-btn" class="btn btn-primary w-100 my-4 mb-2">
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


            </div>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="../public/assets/js/core/popper.min.js"></script>
    <script src="../public/assets/js/core/bootstrap.min.js"></script>
    <script src="../public/assets/js/admin.js"></script>

    <script>
        // Enhance UX + wire AJAX login
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('admin-login-form');
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            const otpField = document.getElementById('otp');
            const btn = document.getElementById('login-btn');

            // Autofocus
            (emailField.value ? passwordField : emailField).focus();

            // Numeric-only OTP
            otpField.addEventListener('input', e => e.target.value = e.target.value.replace(/[^0-9]/g, ''));

            async function postForm(url, data) {
                const resp = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: data
                });
                return resp.json();
            }

            async function validateEmail() {
                const email = emailField.value.trim();
                if (!email) return;
                try {
                    const fd = new FormData();
                    fd.append('email', email);
                    const res = await postForm('./api/validate-login.php', fd);
                    if (res.success && res.exists) {
                        if (res.status !== 'active') {
                            showToasted('Account is not active. Please contact support.', 'error');
                        }
                        if (res.otp_required) {
                            otpField.placeholder = 'Enter 6-digit code (required)';
                        } else {
                            otpField.placeholder = 'Enter 6-digit code (if enabled)';
                        }
                    }
                } catch (e) {
                    // silent
                }
            }

            emailField.addEventListener('blur', validateEmail);

            form.addEventListener('submit', async function() {
                const email = emailField.value.trim();
                const password = passwordField.value;
                const otp = otpField.value.trim();

                if (!email || !password) {
                    showToasted('Email and password are required.', 'error');
                    return;
                }

                btn.disabled = true;
                btn.innerText = 'Signing in...';

                try {
                    const fd = new FormData(form);
                    const res = await postForm('./api/auth-login.php', fd);

                    if (res.success) {
                        showToasted('Login successful.', 'success');
                        setTimeout(() => {
                            window.location.href = res.redirect || 'dashboard.php';
                        }, 500);
                    } else {
                        showToasted(res.message || 'Login failed. Try again.', 'error');
                        if (res.otp_required) {
                            otpField.focus();
                        }
                    }
                } catch (err) {
                    showToasted('Network error. Please try again.', 'error');
                } finally {
                    btn.disabled = false;
                    btn.innerText = 'Sign In';
                }
            });
        });
    </script>
</body>

</html>