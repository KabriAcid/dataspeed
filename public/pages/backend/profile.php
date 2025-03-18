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
        <header class="d-flex justify-content-between align-items-start mb-4">
            <a href="dashboard.php" class="btn btn-secondary btn-sm">Back</a>

        </header>
        <div class="container">
            <!-- Profile Header -->
            <header class="d-flex justify-content-between align-items-center my-4">
                <h5 class="fw-bold">Profile</h5>
            </header>

            <!-- User Info -->
            <div class="text-center">
                <img src="../<?= $_SESSION['user']['photo'] ?>" alt="User Photo" class="profile-avatar">
                <h4 class="fw-bold mt-2"><?= $username; ?> <i class="fa fa-check-circle primary"></i></h4>
                <p class="text-muted"><?= $user_location; ?></p>
            </div>

            <!-- Account Settings -->
            <div class="mt-4">
                <h6 class="text-muted">Account Settings</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><i class="fa fa-user-circle primary me-2"></i> Profile Settings</div>
                        <i class="fa fa-chevron-right"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><i class="fa fa-map-marker primary me-2"></i> Postal Address</div>
                        <i class="fa fa-chevron-right"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><i class="fa fa-history primary me-2"></i> Transaction History</div>
                        <i class="fa fa-chevron-right"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><i class="fa fa-wallet primary me-2"></i> Payments</div>
                        <i class="fa fa-chevron-right"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><i class="fa fa-comments primary me-2"></i> Chat & Support</div>
                        <i class="fa fa-chevron-right"></i>
                    </li>
                </ul>
            </div>
        </div>

        <a href="logout.php" class="btn btn-danger">Exit</a>


    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>