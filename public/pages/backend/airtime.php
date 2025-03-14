<?php
require __DIR__ . '/../../partials/header.php';
require __DIR__ . '/../../../config/config.php'; // Ensure database connection is included
session_start();
?>

<body>

    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="d-flex justify-content-between align-items-start mb-4">
            <a href="dashboard.php">Back</a>
        </header>
        -

        <!-- Bottom navigation -->
        <nav class="mobile-nav">
            <ul>
                <li><a href="#" class="mobile-nav-link" id="home"><i class="fa fa-home"></i></a></li>
                <li><a href="#" class="mobile-nav-link" id="wallet"><i class="fa fa-bell"></i></a></li>
                <li><a href="#" class="mobile-nav-link" id="transactions"><i class="fa fa-list"></i></a></li>
                <li><a href="#" class="mobile-nav-link" id="settings"><i class="fa fa-cog"></i></a></li>
            </ul>
        </nav>

        <!-- FontAwesome CDN -->
        <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>