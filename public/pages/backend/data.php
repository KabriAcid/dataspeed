<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Buy Data</h5>
        </header>


        <!-- Bottom navigation -->
        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
        <!-- FontAwesome CDN -->
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>