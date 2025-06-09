<?php
session_start();

// Check if user is being referred using GET METHOD
if (isset($_GET['referral_code'])) {
    $_SESSION['referral_code'] = $_GET['referral_code'];
    $referral_code = $_SESSION['referral_code'] ? $_SESSION['referral_code'] : '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "DataSpeed" ?></title>
    <link rel="shortcut icon" href="../logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">

    <!-- Toasted JS -->
    <link rel="stylesheet" href="../assets/css/toasted.css" />
    <script src="../assets/js/toasted.js"></script>


    <!-- Add font awesome icons to buttons (note that the fa-spin class rotates the icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <main class="container">
        <div class="form-container text-center">
            <div class="form-top-container">
                <svg id="registration_reset" class="cursor-pointer" width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.60564 1.65147L3.73182 6.5253H16V8.47483H3.73182L8.60564 13.3487L7.22712 14.7272L0 7.50006L7.22712 0.272949L8.60564 1.65147Z"
                        fill="#722F37" />
                </svg>
                <div class="pagination">
                    <span class="page active"></span>
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
                            Continue
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
                        <button type="button" class="btn primary-btn mt-3" id="email-submit">
                            Continue
                        </button>
                    </div>
                </div>
                <!-- OTP VERIFICATION -->
                <div class="form-step d-none">
                    <div class="form-step-header">
                        <h4>Verify Your OTP Code</h3>
                            <p class="text-sm" id="user_email">Enter the 6-digit code sent to your email.</p>
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
                        <button type="button" class="btn primary-btn mt-3" id="phone-submit">
                            Continue
                        </button>
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
                        <button type="button" class="btn primary-btn mt-3" id="names-submit">
                            Continue
                        </button>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="form-step d-none" id="password-step">
                    <div class="form-step-header">
                        <h4>Create your password.</h4>
                        <p class="text-sm">Make sure your password is strong.</p>
                    </div>
                    <div class="form-field">
                        <input type="password" class="input" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-field">
                        <input type="password" class="input" id="confirm-password" name="confirm_password"
                            placeholder="Password">
                    </div>
                    <button type="button" class="btn primary-btn" id="password-submit" onclick="showOverlay()">
                        Finish
                    </button>
                </div>

                <div id="overlay">
                    <div class="loader"></div>
                </div>
            </form>

        </div>
    </main>
</body>
<script>

document.getElementById('registration_reset').addEventListener('click', function(){
    sessionStorage.clear();
    window.location.href = 'register.php';
});
</script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/toggle-password.js"></script>
<script src="../assets/js/multi-step.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>