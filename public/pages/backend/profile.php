<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="d-flex justify-content-between align-items-start mb-4">
            <a href="dashboard.php" class="btn btn-secondary btn-sm">Back</a>

        </header>
        <div class="form-container container">
            <h2 class="text-center mb-4">Profile</h2>
            <form action="" method="post">
                <div class="d-flex justify-content-center">
                    <img src="../<?= $_SESSION['user']['photo']?>" alt="avatar" class="avatar avatar-lg">
                </div>
            </form>
        </div>

        <a href="logout.php" class="btn btn-danger">Exit</a>


    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>