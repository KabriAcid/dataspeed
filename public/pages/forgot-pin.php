<?php
session_start();

require __DIR__ . '/../../config/config.php';
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
                <a href="dashboard.php">
                    <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.60564 1.65147L3.73182 6.5253H16V8.47483H3.73182L8.60564 13.3487L7.22712 14.7272L0 7.50006L7.22712 0.272949L8.60564 1.65147Z"
                            fill="#722F37" />
                    </svg>
                </a>
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
                        <p>Enter the token that was sent to your email</p>
                    </div>
                    <div class="form-field">
                        <input type="text" name="token" id="token" placeholder="Insert your token" class="input">
                        <button type="button" class="btn primary-btn mt-3" id="token-submit">
                            Submit
                        </button>
                    </div>
                    <div class="otp-timer-container">
                        <p>Time remaining: <span id="otp-timer">10:00</span></p>
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

    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>