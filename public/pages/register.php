<?php

session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

if (isset($_GET['referral_code'])) {
    $_SESSION['referral_code'] = $_GET['referral_code'];
    $referral_code = $_SESSION['referral_code'] ? $_SESSION['referral_code'] : '';
}
function set_title($title = null)
{
    $default = "DataSpeed";
    return htmlspecialchars($title ?: $default);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title is set dynamically -->
    <title><?= set_title($title ?? null) ?></title>
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">

    <!-- Font Awesome for icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet">

    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <!-- <script src="../assets/js/lottie-player.js"></script> -->

    <!-- Toasted JS for notifications -->
    <link rel="stylesheet" href="../assets/css/toasted.css" />
    <script src="../assets/js/toasted.js"></script>


    <link rel="stylesheet" href="../assets/css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <main class="container py-4">
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
                    <span class="page"></span>
                    <span class="page"></span>
                    <span class="page"></span>
                </div>

            </div>
            <form id="multi-step-form" method="post" onsubmit="return false;">
                <!-- REFERRAL CODE FIELD -->
                <div class="form-step">
                    <div class="form-step-header">
                        <h4 class="">Do you have a referral code?</h4>
                        <p class="text-sm">Leave empty if no referral code available.</p>
                    </div>
                    <div class="form-field">
                        <input type="text" name="referral_code" id="referral-code"
                            placeholder="Referral code (optional)" class="input"
                            value="<?php echo isset($referral_code) ? $referral_code : ''; ?>" autocomplete="off">
                        <button type="button" class="btn primary-btn mt-3" id="referral-submit">
                            Proceed
                        </button>
                    </div>
                </div>
                <!-- EMAIL VERIFICATION -->
                <div class="form-step d-none">
                    <div class="form-step-header">
                        <h4>What's your email address?</h4>
                        <p class="text-sm">You will receive a verification code, so make sure it is active.</p>
                    </div>
                    <div class="form-field">
                        <input type="email" name="email" id="email" placeholder="Email address" class="input">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn bg-light mt-3 prev-button">
                                Back
                            </button>
                            <button type="button" class="btn primary-btn mt-3" id="email-submit">
                                Proceed
                            </button>
                        </div>
                    </div>
                </div>
                <!-- OTP VERIFICATION -->
                <div class="form-step d-none">
                    <div class="form-step-header">
                        <h4>Verify Your OTP Code</h3>
                            <p>
                                Enter the 6 digit code that was sent to
                                <span id="user-email" class="fw-bold"></span>
                            </p>
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
                        <button type="button" id="verify-otp-btn" class="btn primary-btn mt-3">
                            Verify OTP
                        </button>
                    </div>
                    <div class="otp-timer-container">
                        <p>Time remaining: <span id="otp-timer">03:00</span></p>
                        <a id="resend-otp-btn" class="disabled-link">Resend OTP</a>
                    </div>
                </div>

                <!-- PHONE NUMBER VERIFICATION-->
                <div class="form-step d-none">
                    <div class="form-step-header">
                        <h4>What's your phone number?</h4>
                        <p class="text-sm">Omit the first digit and insert the rest.</p>
                    </div>
                    <div class="form-field">
                        <div class="input-group-container">
                            <span class="input-group-prefix text-sm">
                                <img src="https://flagcdn.com/w40/ng.png" alt=""> +234
                            </span>
                            <input type="text" id="phone" name="phone_number" maxlength="10" placeholder="Phone Number"
                                class="input">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn bg-light mt-3 prev-button">
                                Back
                            </button>
                            <button type="button" class="btn primary-btn mt-3" id="phone-submit">
                                Proceed
                            </button>
                        </div>
                    </div>
                </div>

                <!-- NAMES VERIFICATION -->
                <div class="form-step d-none">
                    <div class="form-step-header">
                        <h4>What's your full name?</h3>
                            <p class="text-sm">Enter your correct name details.</p>
                    </div>
                    <div class="form-field">
                        <input type="text" name="first_name" id="first_name" placeholder="First Name" class="input">
                        <div class="my-3"></div>
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="input">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn bg-light mt-3 prev-button">
                                Back
                            </button>
                            <button type="button" class="btn primary-btn mt-3" id="names-submit">
                                Proceed
                            </button>
                        </div>
                    </div>
                </div>

                <!-- USERNAME -->
                <div class="form-step d-none">
                    <div class="form-step-header">
                        <h4>What's your username?</h3>
                            <p class="text-sm">Enter your favorite username.</p>
                    </div>
                    <div class="form-field">
                        <input type="text" name="username" id="username" placeholder="User Name" class="input">
                        <div class="my-3"></div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn bg-light mt-3 prev-button">
                                Back
                            </button>
                            <button type="button" class="btn primary-btn mt-3" id="username-submit">
                                Proceed
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="form-step d-none" id="password-step">
                    <div class="form-step-header">
                        <h4>Create your password.</h4>
                        <p class="text-sm">Make sure your password is strong.</p>
                    </div>
                    <div class="form-field">
                        <div class="password-wrapper">
                            <input type="password" id="password" class="input" placeholder="Enter your password" style="padding-right: 40px;" />
                            <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                        </div>
                    </div>
                    <div class="form-field">
                        <div class="password-wrapper">
                            <input type="password" id="confirm-password" class="input" placeholder="Enter your password" style="padding-right: 40px;" />
                            <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn bg-light mt-3 prev-button">
                            Back
                        </button>
                        <button type="button" class="btn primary-btn mt-3" id="password-submit">
                            Finish
                        </button>
                    </div>
                </div>

            </form>

        </div>

        <!-- Overlay -->
        <div id="bodyOverlay" class="body-overlay" style="display: none;">
            <div class="overlay-spinner"></div>
        </div>

    </main>
</body>
<script>
    // Reset registration
    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.getElementById('reset-registration');
        resetBtn.addEventListener('click', function() {
            if (sessionStorage.getItem('registration_id')) {
                const registration_id = sessionStorage.getItem('registration_id');
                let context = "register";
                sendAjaxRequest(
                    "reset-registration.php",
                    "POST",
                    "context=" + encodeURIComponent(context) + "&registration_id=" + encodeURIComponent(registration_id),
                    function(response) {
                        if (!response.success) {
                            showToasted(response.message, "error");
                        } else {
                            showToasted(response.message, "success");
                            setTimeout(() => {
                                sessionStorage.clear();
                                window.location.href = "register.php";
                            }, 2000);
                        }
                    }
                );
            }
        });
    });

    // Change referral submit button text based on input
    const referralInput = document.getElementById('referral-code');
    const referralSubmitBtn = document.getElementById('referral-submit');

    function updateReferralBtnText() {
        if (referralInput.value.trim().length > 0) {
            referralSubmitBtn.innerText = 'Proceed';
        } else {
            referralSubmitBtn.innerText = 'Skip';
        }
    }

    referralInput.addEventListener('input', updateReferralBtnText);
    // Set initial state on page load
    updateReferralBtnText();
</script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/toggle-password.js"></script>
<script src="../assets/js/multi-step.js"></script>
<?php require __DIR__ . '/../partials/auth-modal.php'; ?>
<?php require __DIR__ . '/../partials/scripts.php'; ?>

</html>