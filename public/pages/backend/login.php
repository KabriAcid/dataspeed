<?php require __DIR__ . '/../../partials/header.php'; ?>

<body>
    <?php require __DIR__ . '/../../partials/navbar.php';
    ?>

</body>
<main class="container">
    <div class="form-container text-center">
        <div class="form-top-container">
            <a href="">
                <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.60564 1.65147L3.73182 6.5253H16V8.47483H3.73182L8.60564 13.3487L7.22712 14.7272L0 7.50006L7.22712 0.272949L8.60564 1.65147Z" fill="#722F37" />
                </svg>
            </a>
        </div>
        <form method="post" onsubmit="return false;">
            <div id="login-form">
                <h3>Welcome Back!</h3>
                <p>Enter your correct login details to continue.</p>
                <div class="form-field">
                    <div>
                        <input type="text" id="user" name="user" placeholder="Email address or Phone Number" class="input">
                    </div>
                    <div class="my-3"></div>
                    <div>
                        <input type="password" id="password" name="password" placeholder="Password" class="input">
                    </div>
                    <label for="" class="error-label" id="email-error"></label>

                    <p class="text-right"><a href="forgot-password.php" class="text-secondary">Forgot password?</a></p>
                    <button type="submit" class="btn primary-btn mt-3" name="login" id="login">
                        <i class="fa fa-spinner fa-spin d-none" id="spinner-icon"></i>
                        Login
                    </button>
                </div>
                <p class="text-center">Don't have an account? <a href="register.php" class="link">Register</a></p>
                <p class="copyright">Copyright @DreamCodes</p>
            </div>
        </form>
    </div>
</main>
<script src="../../assets/js/auth.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>