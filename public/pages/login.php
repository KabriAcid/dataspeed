<?php
$success = null;
if (isset($_GET['success'])) {
    $success = $_GET['success'];
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
    <?php #require __DIR__ . '/../partials/navbar.php';
    ?>

</body>
<main class="container">
    <?php
    if ($success == 1) {
        echo "<script>showToasted('Registration Successful', 'success')</script>";
        // Redirect to the same page without the success parameter
        echo "<script>window.location.href='login.php';</script>";
        exit;
    } elseif ($success == 2) {
        echo "<script>showToasted('Password reset Successfully', 'success')</script>";
        // Redirect to the same page without the success parameter
        echo "<script>window.location.href='login.php';</script>";
        exit;
    }
    if (isset($_GET['expired'])) {
        $expired = $_GET['expired'];
        if ($expired == 1) {
            echo "<script>
            showToasted('Your session has expired. Please login again.', 'error')
            </script>";
        }
    }
    ?>
    <div class="form-container text-center">
        <div class="form-top-container">
            <a href="../../../index.php">
                <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.60564 1.65147L3.73182 6.5253H16V8.47483H3.73182L8.60564 13.3487L7.22712 14.7272L0 7.50006L7.22712 0.272949L8.60564 1.65147Z"
                        fill="#722F37" />
                </svg>
            </a>
        </div>
        <form method="post" onsubmit="return false;" class="mt-5">
            <div id="login-form">
                <h3>Welcome Back!</h3>
                <p class="text-sm">Enter your correct login details to continue.</p>
                <div class="form-field">
                    <div>
                        <input type="text" id="user" name="user" placeholder="Email address or Phone Number"
                            class="input">
                    </div>
                    <div class="my-3"></div>
                    <div class="password-wrapper">
                        <input type="password" id="password" class="input" placeholder="Enter your password" style="padding-right: 40px;" />
                        <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                    </div>

                    <p class="text-end"><a href="forgot_password.php" class="text-secondary text-sm">Forgot
                            password?</a></p>
                    <button type="submit" class="btn primary-btn mt-3" name="login" id="login">
                        Login
                    </button>
                </div>
                <p class="text-center text-sm">Don't have an account? <a href="register.php" class="link">Register</a>
                </p>
                <p class="copyright text-xs">Copyright @DreamCodes</p>
            </div>
        </form>
    </div>
</main>

<script src="../assets/js/toggle-password.js"></script>
<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/auth.js"></script>
<?php require __DIR__ . '/../partials/scripts.php'; ?>
<?php require __DIR__ . '/../partials/auth-modal.php'; ?>

</html>