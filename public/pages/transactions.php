<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">Transactions</h5>
                <div class="notification-icon">
                    <a href="notifications.php">
                        <i class="ni ni-bell-55 fs-5 text-gradient text-dark"></i>
                        <span class="notification-badge"></span>
                    </a>
                </div>
            </div>
        </header>
        <!-- Filters -->
        <div class="card mb-3">
            <form method="get" class="card-body p-3 row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <label for="start" class="form-label text-sm mb-1">Start date</label>
                    <input type="date" id="start" name="start" class="form-control" value="<?= htmlspecialchars($_GET['start'] ?? '') ?>">
                </div>
                <div class="col-12 col-md-4">
                    <label for="end" class="form-label text-sm mb-1">End date</label>
                    <input type="date" id="end" name="end" class="form-control" value="<?= htmlspecialchars($_GET['end'] ?? '') ?>">
                </div>
                <div class="col-12 col-md-4">
                    <label for="" class="d-block"></label>
                    <button type="submit" class="btn btn-dark">Filter</button>
                    <a class="btn btn-outline-secondary" href="transactions.php">Reset</a>
                </div>
            </form>
        </div>

        <!-- Transaction body -->
        <div>
            <?php
            $start = isset($_GET['start']) && $_GET['start'] !== '' ? $_GET['start'] : null;
            $end = isset($_GET['end']) && $_GET['end'] !== '' ? $_GET['end'] : null;

            if ($start || $end) {
                $transactions = getTransactionsFiltered($pdo, (int)$user_id, $start, $end, 200);
            } else {
                $transactions = getTransactions($pdo, $user_id, 200);
            }

            if (!$transactions) {
                echo '<div class="text-center text-muted my-5">'
                    . '<svg width="96" height="96" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">'
                    . '<path d="M3 7a2 2 0 0 1 2-2h6l2 2h6a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z" stroke="#c0c4cc" stroke-width="1.5" fill="none"/>'
                    . '<path d="M7 13h10M7 16h6" stroke="#c0c4cc" stroke-width="1.5" stroke-linecap="round"/>'
                    . '</svg>'
                    . '<p class="mt-3 text-sm">No transactions found for the selected range.</p>'
                    . '</div>';
            } else {
                $grouped = groupTransactionsByDate($transactions);
                foreach ($grouped as $label => $items) {
            ?>
                    <p class="text-sm mb-2 mt-3"><span class="font-weight-bold"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span></p>
                    <div class="transaction-list">
                        <?php foreach ($items as $transaction) {
                            $iconClass = trim(($transaction['icon'] ?? '') . ' ' . ($transaction['color'] ?? ''));
                            $icon = $iconClass ? "<i class='" . htmlspecialchars($iconClass, ENT_QUOTES, 'UTF-8') . "'></i>" : "<i class='ni ni-credit-card text-secondary'></i>";
                            $textColor = ($transaction['direction'] ?? '') === 'credit' ? 'text-success' : 'text-danger';
                            $amount = (float)($transaction['amount'] ?? 0);
                            $formattedAmount = number_format($amount, 2);
                            $date = date("h:i A . d F, Y.", strtotime($transaction['created_at'] ?? 'now'));
                            $prefix = ($transaction['direction'] ?? '') === 'credit' ? '+\u20a6' : '-\u20a6';
                        ?>
                            <div class='d-flex justify-content-between align-items-top mb-3 pb-2'>
                                <div class='d-flex align-items-top'>
                                    <div class='transaction-icon'><?= $icon ?></div>
                                    <div class="mx-3">
                                        <h6 class='mb-0 text-capitalize'><?= htmlspecialchars($transaction['type'] ?? '', ENT_QUOTES, 'UTF-8') ?></h6>
                                        <p class='text-sm text-secondary mb-0'><?= $date ?></p>
                                    </div>
                                </div>
                                <span class='<?= $textColor ?> fw-semibold text-sm'><?= $prefix . $formattedAmount ?></span>
                            </div>
                        <?php } ?>
                    </div>
            <?php
                }
            }
            ?>
        </div>
        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
        <footer class="my-4">
            <p class="text-xs text-center text-secondary">Copyright &copy; Dreamerscodes 2025. All rights reserved.</p>
        </footer>


    </main>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>