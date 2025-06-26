<?php
session_start();

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/initialize.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/header.php';

$loggedInPhone = isset($user['phone_number']) ? $user['phone_number'] : '';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">Forgot PIN</h5>
                <div class="notification-icon">
                    <a href="notifications.php">
                        <i class="ni ni-bell-55 fs-5 text-gradient text-dark"></i>
                        <span class="notification-badge"></span>
                    </a>
                </div>
            </div>
        </header>

        <div class="form-container text-center">
            <div class="form-top-container">
                <svg id="reset-registration" class="cursor-pointer" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.1679 8C19.6248 4.46819 16.1006 2 12 2C6.81465 2 2.5511 5.94668 2.04938 11M22 3V7.4C22 7.73137 21.7314 8 21.4 8H17M2.88146 16C4.42458 19.5318 7.94874 22 12.0494 22C17.2347 22 21.4983 18.0533 22 13M2.04932 21V16.6C2.04932 16.2686 2.31795 16 2.64932 16H7.04932" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="pagination">
                    <span class="page active"></span>
                    <span class="page"></span>
                    <span class="page"></span>
                    <span class="page"></span>
                </div>

            </div>
            <form method="post" onsubmit="return false;">
                <!-- PIN -->
                <div class="form-step">
                    <div class="form-step-header">
                        <h3>Forgot PIN?</h3>
                        <p class="text-sm">Enter your login password below.</p>
                    </div>
                    <div class="form-field">
                        <input type="password" class="input" id="password" name="password" placeholder="Password">
                        <button type="button" class="btn primary-btn mt-3" id="password-submit">
                            Proceed
                        </button>
                    </div>
                </div>
                <!-- EMAIL -->
                <div class="form-step d-none">
                    <div class="form-step-header">
                        <h3>Enter email address</h3>
                        <p class="text-sm">No worries, an authentication code will be sent to your email.</p>
                    </div>
                    <div class="form-field">
                        <input type="email" name="email" id="email" placeholder="Email address" class="input">
                        <button type="button" class="btn primary-btn mt-3" id="email-submit">
                            Proceed
                        </button>
                    </div>
                </div>
                <!-- TOKEN -->
                <div class="form-step d-none">
                    <div class="form-step-header">
                        <h3>Enter generated token</h3>
                        <p>
                            Enter the token that was sent to
                            <span id="token-email" class="fw-bold"></span>
                        </p>
                    </div>
                    <div class="form-field">
                        <input type="text" name="token" id="token" placeholder="Insert your token" class="input">
                        <button type="button" class="btn primary-btn mt-3" id="token-submit">
                            Submit
                        </button>
                    </div>
                    <div class="otp-timer-container">
                        <p>Time remaining: <span id="otp-timer">2:00</span></p>
                    </div>
                </div>
                <!-- PASSWORD (PIN) -->
                <div class="form-step d-none" id="pin-step">
                    <div class="form-step-header">
                        <h3>Reset your PIN.</h3>
                        <p>Enter a new 4-digit PIN.</p>
                    </div>
                    <div class="form-field">
                        <input type="password" class="input" id="pin" name="pin" placeholder="PIN" maxlength="4" inputmode="numeric">
                    </div>
                    <div class="form-field">
                        <input type="password" class="input" id="confirm-pin" name="confirm_pin" placeholder="Confirm PIN" maxlength="4" inputmode="numeric">
                    </div>
                    <button type="button" class="btn primary-btn" id="pin-submit">
                        Finish
                    </button>
                </div>
            </form>

        </div>

        <!-- Bottom navigation -->
        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
        <?php require __DIR__ . '/../partials/pinpad.php' ?>

    </main>
    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/pin-reset.js"></script>
    <script src="../assets/js/pinpad.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resetBtn = document.getElementById('reset-registration');
            resetBtn.addEventListener('click', function() {
                if (sessionStorage.getItem('reset_email')) {
                    const reset_email = sessionStorage.getItem('reset_email');
                    let context = "pin";
                    sendAjaxRequest(
                        "reset-registration.php",
                        "POST",
                        "context=" + encodeURIComponent(context) + "&email=" + encodeURIComponent(email),
                        function(response) {
                            if (!response.success) {
                                showToasted(response.message, "error");
                            } else {
                                showToasted(response.message, "success");
                                setTimeout(() => {
                                    sessionStorage.clear();
                                    window.location.href = "dashboard.php";
                                }, 2000);
                            }
                        }
                    );
                }
            });
        });
    </script>

    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>