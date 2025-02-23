<?php require __DIR__ . '/../../partials/header.php'; ?>

<?php
session_start();
?>
<main class="container">
    <div class="form-container text-center">
        <div class="form-top-container">
            <a href="../../../index.php">
                <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.60564 1.65147L3.73182 6.5253H16V8.47483H3.73182L8.60564 13.3487L7.22712 14.7272L0 7.50006L7.22712 0.272949L8.60564 1.65147Z" fill="#722F37" />
                </svg>
            </a>
            <div class="pagination">
                <span class="page active"></span>
                <span class="page"></span>
                <span class="page"></span>
                <span class="page"></span>
                <span class="page"></span>
            </div>

        </div>
        <form id="multi-step-form" method="post" onsubmit="return false;">
            <!-- EMAIL VERIFICATION -->
            <div class="form-step">
                <div class="form-step-header">
                    <h3>What's your email address?</h3>
                    <p>You will receive a verification code, so make sure it is active.</p>
                </div>
                <div class="form-field">
                    <input type="email" name="email" id="email" placeholder="Email address" class="input">
                    <label for="" class="error-label" id="email-error"></label>
                    <button type="button" class="btn primary-btn mt-3" id="email-submit">
                        <i class="fa fa-spinner fa-spin d-none" id="spinner-icon"></i>
                        Continue
                    </button>
                </div>
            </div>
            <!-- OTP VERIFICATION -->
            <div class="form-step d-none">
                <div class="form-step-header">
                    <h3 class="form-step-header">Verify Your OTP Code</h3>
                    <p class="form-step-para">Enter the 6-digit code sent to your email.</p>
                </div>
                <div class="form-field">
                    <div class="otp-container my-4">
                        <input type="text" maxlength="1" class="otp-input">
                        <input type="text" maxlength="1" class="otp-input">
                        <input type="text" maxlength="1" class="otp-input">
                        <input type="text" maxlength="1" class="otp-input">
                        <input type="text" maxlength="1" class="otp-input">
                        <input type="text" maxlength="1" class="otp-input">
                    </div>
                    <label id="otp-error" class="error-label"></label>
                    <button type="button" id="verify-otp-btn" class="btn primary-btn mt-3">
                        <i class="fa fa-spinner fa-spin d-none" id="spinner-icon"></i>
                        Verify OTP
                    </button>
                </div>
                <div class="otp-timer-container">
                    <p>Time remaining: <span id="otp-timer">10:00</span></p>
                </div>
            </div>

            <!-- PHONE NUMBER VERIFICATION-->
            <div class="form-step d-none">
                <div class="form-step-header">
                    <h3>What's your phone number?</h3>
                    <p>Omit the first digit and insert the rest.</p>
                </div>
                <div class="form-field">
                    <div class="phone-container">
                        <span class="phone-prefix">
                            <img src="https://flagcdn.com/w40/ng.png" alt="Nigeria Flag"> +234
                        </span>
                        <input type="tel" id="phone" name="phone_number" placeholder="Phone Number" class="input">
                    </div>
                    <label for="" class="error-label" id="phone-error"></label>
                    <button type="button" class="btn primary-btn mt-3" id="phone-submit">
                        <i class="fa fa-spinner fa-spin d-none" id="spinner-icon"></i>
                        Continue
                    </button>
                </div>
            </div>

            <!-- NAMES VERIFICATION -->
            <div class="form-step d-none">
                <div class="form-step-header">
                    <h3>What's your full name?</h3>
                    <p>Enter your correct name details.</p>
                </div>
                <div class="form-field">
                    <input type="text" name="first_name" id="first_name" placeholder="First Name" class="input">
                    <div class="my-3"></div>
                    <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="input">
                    <label for="" class="error-label" id="names-error"></label>
                    <button type="button" class="btn primary-btn mt-3" id="names-submit">
                        <i class="fa fa-spinner fa-spin d-none" id="spinner-icon"></i>
                        Continue
                    </button>
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="form-step d-none" id="password-step">
                <div class="form-step-header">
                    <h3>Create your password.</h3>
                    <p>Make sure your password is strong.</p>
                </div>
                <div class="form-field">
                    <input type="password" class="input" id="password" name="password" placeholder="Password">
                </div>
                <div class="form-field">
                    <input type="password" class="input" id="confirm-password" name="confirm_password" placeholder="Password">
                </div>
                <label for="" class="error-label" id="password-error"></label>
                <button type="button" class="btn primary-btn" id="password-submit">
                    <i class="fa fa-spinner fa-spin d-none" id="spinner-icon"></i>
                    Finish
                </button>
            </div>

        </form>

    </div>
</main>
<script>
    let countdown = 600; // 10 minutes in seconds
    const timerDisplay = document.getElementById("otp-timer");
    const verifyBtn = document.getElementById("verify-otp-btn");

    function updateTimer() {
        if (!timerDisplay) return; // Avoid errors if element is missing

        const minutes = Math.floor(countdown / 60);
        const seconds = countdown % 60;
        timerDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

        if (countdown === 0) {
            timerDisplay.textContent = "OTP Expired! Request a new one.";
            verifyBtn.disabled = true;
            clearInterval(timerInterval);
        }

        countdown--;
    }

    const timerInterval = setInterval(updateTimer, 1000); // Start the countdown
</script>
<script src="../../assets/js/multi-step.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>