<?php
$success = null;
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "DataSpeed" ?></title>
    <link rel="shortcut icon" href="../../logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">
    <!-- Add font awesome icons to buttons (note that the fa-spin class rotates the icon) -->

    <!-- Toasted JS -->
    <link rel="stylesheet" href="../../assets/css/toasted.css" />
    <script src="../../assets/js/toasted.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">

</head>

<body>
    <?php #require __DIR__ . '/../../partials/navbar.php';
    ?>

</body>
<main class="container">
    <?php
    if ($success == 1) {
        echo "<script>showToasted('Registration Successful', 'success')</script>";
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
                    <div>
                        <input type="password" id="password" name="password" placeholder="Password" class="input">
                    </div>
                    <p class="text-end"><a href="forgot-password.php" class="text-secondary text-sm">Forgot
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
<script src="../../assets/js/auth.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>