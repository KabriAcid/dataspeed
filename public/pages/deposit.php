<?php
session_start();
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../../config/config.php';

$public_key = htmlspecialchars($_ENV['FLW_PUBLIC_KEY']);
$email = htmlspecialchars($_SESSION['user_id']['email']);
$phone_number = htmlspecialchars($_SESSION['user_id']['phone_number']);
$names = $_SESSION['user_id']['first_name'] . " " . $_SESSION['user_id']['last_name'];
?>

<body>

    <main class="container-fluid py-4">
        <header class="d-flex justify-content-between align-items-start mb-4">
            <a href="dashboard.php" class="btn btn-secondary btn-sm">Back</a>

        </header>


        <!-- BOTTOM NAVIGATION -->
        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://checkout.flutterwave.com/v3.js"></script>

    </main>

</body>

</html>