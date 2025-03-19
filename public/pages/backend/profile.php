<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../partials/header.php';

// Check if user session exists
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['user_id'];
    $username = $_SESSION['user']['first_name'];
    $user_location = $_SESSION['user']['city'] ?? 'Nigeria';
} else {
    header('Location: login.php');
}
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="d-flex justify-content-between align-items-center my-3">
            <h5 class="fw-bold">Profile</h5>
            <i class="fas fa-ellipsis-h"></i>
        </header>

        <!-- User Info -->
        <div class="text-center">
            <img src="../<?= $_SESSION['user']['photo'] ?>" alt="User Photo" class="profile-avatar">
            <h4 class="fw-bold mt-2"><?= $username; ?> <i class="fa fa-check-circle text-primary"></i></h4>
            <p class="text-muted"><?= $user_location; ?></p>
        </div>

        <!-- Account Settings -->
        <div class="main-body">
            <p class="text-muted">Account Settings</p>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="icon-container">
                            <i class="fa fa-user primary"></i>
                        </div>
                        <p class="mx-2">Profile Setting</p>
                    </div>
                    <div>
                        <i class="fa fa-chevron-right float-end"></i>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="icon-container">
                            <i class="fa fa-map-marker-alt primary"></i>
                        </div>
                        <p class="mx-2">Postal Address</p>
                    </div>
                    <div>
                        <i class="fa fa-chevron-right float-end"></i>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="icon-container">
                            <i class="fa fa-history primary"></i>
                        </div>
                        <p class="mx-2">Transaction History</p>
                    </div>
                    <div>
                        <i class="fa fa-chevron-right float-end"></i>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="icon-container">
                            <i class="fa fa-wallet primary"></i>
                        </div>
                        <p class="mx-2">Payments</p>
                    </div>
                    <div>
                        <i class="fa fa-chevron-right float-end"></i>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="icon-container">
                            <i class="fa fa-comments primary"></i>
                        </div>
                        <p class="mx-2">Chat & Help</p>
                    </div>
                    <div>
                        <i class="fa fa-chevron-right float-end"></i>
                    </div>
                </div>
            </div>
        </div>

        <a href="logout.php" class="mt-5 btn btn-danger">Exit</a>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>