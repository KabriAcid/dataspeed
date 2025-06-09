<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5 d-flex justify-content-between align-items-center">
            <span></span>
            <h6 class="text-center fw-bold">Transactions</h6>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12.0049 5.99512L12.0049 6.00512M12.0049 17.9951L12.0049 18.0051M12.0049 11.9951L12.0049 12.0051"
                    stroke="#141C25" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

        </header>

        <!-- Transaction body -->
        <div class="main-body">
            <p class="text-muted fw-bold">Today</p>
            <div class="transaction-list">
                <?php
                $transactions = getTransactions($pdo, $user_id);

                // var_dump($transactions);

                if (!$transactions) {
                    echo '<p class="text-center text-muted">No transactions yet.</p>';
                } else {
                    foreach ($transactions as $transaction) {
                        $icon = getTransactionIcon($transaction['type']);
                        $textColor = $transaction['type'] == 'Deposit' ? 'text-success' : 'text-danger';
                        $formattedAmount = number_format($transaction['amount'], 2);
                        $date = date("M d, Y H:m", strtotime($transaction['created_at']));
                        $prefix = ($transaction['type'] === 'Deposit') ? '+' : '-';

                        if ($transactions) {
                ?>
                <div class='d-flex justify-content-between align-items-start mb-3 pb-2'>
                    <div class='d-flex align-items-center gap-3'>
                        <div class='transaction-icon p-2 text-white'><?= $icon ?></div>
                        <div>
                            <h6 class='mb-0'><?= $transaction['type'] ?></h6>
                            <p class='text-sm text-secondary mb-0'><?= $date ?></p>
                        </div>
                    </div>
                    <span class='<?= $textColor ?> fw-semibold text-sm'><?= $prefix . $formattedAmount ?></span>
                </div>
                <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
        <footer class="my-4">
            <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
        </footer>


    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>