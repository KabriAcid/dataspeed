<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../partials/header.php';
require __DIR__ . '/../../../functions/Model.php';

// Check if user session exists
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['user_id'];
} else {
    header('Location: login.php');
}
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h6 class="text-center fw-bold">Transactions</h6>
        </header>

        <!-- Transaction body -->
        <div class="main-body">
            <p class="text-muted fw-bold">Today</p>
            <div class="transaction-list">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Item 1 -->
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Sub-Item 1 -->
                        <div class="transaction-icon">
                            <?php
                            echo getTransactionIcon('Deposit');
                            ?>
                        </div>
                        <!-- Sub-Item 2 -->
                        <div class="mx-3">
                            <h6 class="mb-0">Airtime purchase</h6>
                            <p class="text-sm text-secondary mb-0">12 May 2025, 13:44</p>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div>
                        <span class="credit-amount text-success fw-bold">&#8358;300</span>
                    </div>
                </div>
            </div>
        </div>
        <footer class="my-4">
            <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
        </footer>


    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>