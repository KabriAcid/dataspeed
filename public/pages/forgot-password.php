<?php
session_start();
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
    <link rel="shortcut icon" href="../logo.svg" type="image/x-icon">
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

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>


    <link rel="stylesheet" href="../assets/css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <main class="container">
        <div class="form-container text-center">
            <div class="form-top-container">
                <a href="login.php">
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
                </div>

            </div>
            <form id="" method="post" onsubmit="return false;">
                <!-- EMAIL ADDRESS -->
                <div class="form-step">
                    <div class="form-step-header">
                        <h3>Forgot password?</h3>
                        <p class="text-sm">No worries, an authentication code will be sent to your email.</p>
                    </div>
                    <div class="form-field">
                        <input type="email" name="email" id="email" placeholder="Email address" class="input">
                        <button type="button" class="btn primary-btn mt-3" id="email-submit">
                            Continue
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
                <!-- PASSWORD -->
                <div class="form-step d-none" id="password-step">
                    <div class="form-step-header">
                        <h3>Reset your password.</h3>
                        <p>Make sure your password is strong.</p>
                    </div>
                    <div class="form-field">
                        <input type="password" class="input" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-field">
                        <input type="password" class="input" id="confirm-password" name="confirm_password"
                            placeholder="Password">
                    </div>
                    <label for="" class="error-label" id="password-error"></label>
                    <button type="button" class="btn primary-btn" id="password-submit">
                        Finish
                    </button>
                </div>

            </form>

        </div>
    </main>
</body>
<script src="../assets/js/ajax.js"></script>
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
            verifyBtn.classList.add('inactive-btn');
            verifyBtn.style.cursor = 'not-allowed';
            clearInterval(timerInterval);
        }

        countdown--;
    }

    const timerInterval = setInterval(updateTimer, 1000);
</script>
<script src="../assets/js/password-reset.js"></script>
<?php require __DIR__ . '/../partials/scripts.php'; ?>
<?php require __DIR__ . '/../partials/auth-modal.php'; ?>

</html>