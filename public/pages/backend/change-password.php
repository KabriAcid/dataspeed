<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../partials/header.php';

// Check if user session exists
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['user_id'];
    $username = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];
    $user_location = $_SESSION['user']['city'] ?? 'Nigeria';
} else {
    header('Location: login.php');
}
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h6 class="text-center fw-bold">Password & PIN</h6>
        </header>

        <!-- Account Settings -->
        <div class="main-body mt-5">
            <p class="text-muted fw-bold">Change Password</p>
            <div class="tabs-container">
                <div class="between">
                    <div class="w-100 text-center p-2" id="password-tab" class="active-tab">
                        <h6 class="mb-0">Password</h6>
                    </div>
                    <div class="w-100 text-center p-2" id="pin-tab">
                        <h6 class="mb-0">PIN</h6>
                    </div>
                </div>
            </div>
            <!-- PASSWORD -->
            <div class="box-shadow bg-white p-3">
                <div class="form-field">
                    <input type="password" class="input" id="password" name="password" placeholder="Password">
                </div>
                <div class="form-field">
                    <input type="password" class="input" id="confirm-password" name="confirm_password" placeholder="Password">
                </div>
                <span for="" class="error-label" id="password-error"></span>
                <button type="button" class="btn w-100 primary-btn" id="password-submit">
                    <i class="fa fa-spinner d-none fa-spin" id="spinner-icon"></i>
                    UPDATE
                </button>
            </div>
        </div>

        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>
    <footer class="my-4">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
    </footer>

<script>
    document.getElementById('pin-tab').addEventListener('click', function(){
        document.getElementById('password').style.display = 'none';
    })
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>