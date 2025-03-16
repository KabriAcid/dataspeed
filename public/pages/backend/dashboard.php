<?php
session_start();
require __DIR__ . '/../../partials/header.php';
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
// var_dump($_SESSION['user']);

// Check if user session exists
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['user_id'];
    $username = $_SESSION['user']['first_name'];
} else {
    $username = "Unknown User"; // Fallback if session is not set
}
?>

<body>

    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class=" fs-2 fw-bold mb-0">Welcome back,</h1>
                <p class="text-secondary"><?= $username; ?></p>
            </div>
            <div>
                <a href="profile.php">
                    <span>
                        <img src="../<?= $_SESSION['user']['photo'] ?>" alt="photo" class="profile-avatar">
                    </span>
                </a>
            </div>
        </header>

        <!-- Balance Section -->
        <div class="card mb-4">
            <div class="card-body bg-none">
                <div class="">
                    <p class="text-secondary text-center mb-0">Total Balance</p>
                    <!-- <button class="btn btn-link text-secondary p-0" id="toggleBalance">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button> -->
                </div>
                <h2 class="display-5 fw-bold text-center " id="balanceAmount"><?= "&#8358;" . showBalance($pdo, $user_id) ?></h2>
                <h2 class="display-5 fw-bold text-center d-none" id="hiddenBalance">••••••</h2>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row g-4 mb-4">
            <div class="col-4">
                <div class="d-flex flex-column align-items-center">
                    <a href="airtime.php" class="action-button bg-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m22 2-7 20-4-9-9-4Z" />
                            <path d="M22 2 11 13" />
                        </svg>
                    </a>
                    <span class="text-secondary mt-2">Airtime</span>
                </div>
            </div>
            <!--  -->
            <div class="col-4">
                <div class="d-flex flex-column align-items-center">
                    <a href="data.php" class="action-button bg-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12c0 1.2-4 6-9 6s-9-4.8-9-6c0-1.2 4-6 9-6s9 4.8 9 6Z" />
                            <path d="M12 12h.01" />
                        </svg>
                    </a>
                    <span class="text-secondary mt-2">Data</span>
                </div>
            </div>
            <!--  -->
            <div class="col-4">
                <div class="d-flex flex-column align-items-center">
                    <a href="deposit.php" class="action-button bg-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                    </a>
                    <span class="text-secondary mt-2">Deposit</span>
                </div>
            </div>
            <div class="col-4">
                <div class="d-flex flex-column align-items-center">
                    <a href="deposit.php" class="action-button bg-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                    </a>
                    <span class="text-secondary mt-2">JAMB</span>
                </div>
            </div>
            <div class="col-4">
                <div class="d-flex flex-column align-items-center">
                    <button class="action-button bg-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 7h-9" />
                            <path d="M14 17H5" />
                            <circle cx="17" cy="17" r="3" />
                            <circle cx="7" cy="7" r="3" />
                        </svg>
                    </button>
                    <span class="text-secondary mt-2">Transfer</span>
                </div>
            </div>
            <div class="col-4">
                <div class="d-flex flex-column align-items-center">
                    <button class="action-button bg-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20" />
                        </svg>
                    </button>
                    <span class="text-secondary mt-2">Bills</span>
                </div>
            </div>
        </div>

        <!-- Transactions Section -->
        <div class="card p-0">
            <div class="card-header text-white">
                <h4 class="fw-semibold mb-0">Recent Transactions</h4>
            </div>
            <div class="card-body">
                <div class="transaction-list">
                    <?php
                    $transactions = getTransactions($pdo, $user_id);

                    // var_dump($transactions);

                    if (!$transactions) {
                        echo '<p class="text-center text-muted">No transactions yet.</p>';
                    } else {
                        foreach ($transactions as $transaction) {
                            $icon = getTransactionIcon($transaction['transaction_type']);
                            $textColor = $transaction['transaction_type'] == 'Deposit' ? 'text-success' : 'text-danger';
                            $formattedAmount = number_format($transaction['amount'], 2);
                            $date = date("M d, Y", strtotime($transaction['created_at']));
                            $prefix = ($transaction['transaction_type'] === 'Deposit') ? '+' : '-';

                            if ($transactions) {
                    ?>
                                <div class='d-flex justify-content-between align-items-start mb-3 border-bottom pb-2'>
                                    <div class='d-flex align-items-center gap-3'>
                                        <div class='transaction-icon bg-dark rounded-circle p-2 text-white'><?= $icon ?></div>
                                        <div>
                                            <p class='mb-0 fw-medium'><?= $transaction['transaction_type'] ?></p>
                                            <p class='text-secondary small mb-0'><?= $date ?></p>
                                        </div>
                                    </div>
                                    <span class='<?= $textColor ?> fw-semibold'><?= $prefix . $formattedAmount ?></span>
                                </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>

        <!-- FontAwesome CDN -->
    </main>

    <script>
        document.getElementById('toggleBalance').addEventListener('click', function() {
            const balanceAmount = document.getElementById('balanceAmount');
            const hiddenBalance = document.getElementById('hiddenBalance');
            const eyeIcon = this.querySelector('svg');

            if (balanceAmount.classList.contains('d-none')) {
                balanceAmount.classList.remove('d-none');
                hiddenBalance.classList.add('d-none');
                eyeIcon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
            } else {
                balanceAmount.classList.add('d-none');
                hiddenBalance.classList.remove('d-none');
                eyeIcon.innerHTML = '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/>';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>