<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h5 class="fw-bold">Transactions</h5>
                <span></span>
            </div>
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