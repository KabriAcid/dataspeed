<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';

// var_dump($_SESSION);
?>

<body class="">

    <main class="container-fluid py-4 mb-5">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <!-- Left Content -->
            <div class="d-flex align-items-center">
                <a href="profile.php">
                    <img src="../<?= $user['photo'] ?>" alt="photo" class="profile-avatar">
                </a>
                <div class="ms-3">
                    <h4 class="fs-4 fw-bold mb-0 text-capitalize"><?= $user['first_name'] ?? 'Guest'; ?></h4>
                    <p class="text-secondary text-sm m-0">Welcome back,</p>
                </div>
            </div>

            <!-- Right Content: Notification -->
            <div class="notification-icon">
                <a href="notifications.php">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.73 20C13.5542 20.3031 13.3019 20.5547 12.9982 20.7295C12.6946 20.9044 12.3504 20.9965 12 20.9965C11.6496 20.9965 11.3054 20.9044 11.0018 20.7295C10.6982 20.5547 10.4458 20.3031 10.27 20M17.3333 9C17.3333 7.4087 16.7714 5.88258 15.7712 4.75736C14.771 3.63214 13.4145 3 12 3C10.5855 3 9.22896 3.63214 8.22876 4.75736C7.22857 5.88258 6.66667 7.4087 6.66667 9C6.66667 16 4 17.5 4 17.5H20C20 17.5 17.3333 16 17.3333 9Z"
                            stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="notification-badge"></span>
                </a>
            </div>
        </header>

        <!-- Balance Section -->
        <div>
            <p class="text-secondary mb-0 text-sm">Total Balance</p>
            <div class="d-flex align-items-end">
                <h2 class="display-5 fw-bold" id="balanceAmount"><?= "&#8358;" . showBalance($pdo, $user_id) ?></h2>
                <h2 class="display-5 fw-bold text-center d-none" id="hiddenBalance">********</h2>
                <button class="btn btn-link text-secondary p-0 mx-1" id="toggleBalance">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3 13C6.6 5 17.4 5 21 13M9 14C9 15.6569 10.3431 17 12 17C13.6569 17 15 15.6569 15 14C15 12.3431 13.6569 11 12 11C10.3431 11 9 12.3431 9 14Z"
                            stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- ✅ Account Details Banner -->
        <?php if ($user): ?>
        <div class="account-banner bg-white border-0 rounded shadow-xl p-4 my-4 animate-fade-in">
            <h4 class="mb-4 text-dark fw-bold border-start border-4 border-danger ps-3">Bank Account Details</h4>
            <div class="row text-dark">
                <div class="col-md-4 mb-3">
                    <h6 class="text-uppercase text-muted mb-1">Account Number</h6>
                    <p class="fs-5 fw-semibold d-inline" id="account-number">
                        <?= htmlspecialchars($user['virtual_account']) ?>
                    </p>
                    <i class="fa fa-copy ms-2 text-primary" id="copy-button" style="cursor: pointer;"></i>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="text-uppercase text-muted mb-1">Account Name</h6>
                    <p class="fs-5 fw-semibold"><?= htmlspecialchars($user['account_name']) ?></p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="text-uppercase text-muted mb-1">Bank Name</h6>
                    <p class="fs-5 fw-semibold"><?= htmlspecialchars($user['bank_name']) ?></p>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning my-3">Unable to retrieve account details.</div>
        <?php endif; ?>


        <!-- Action Buttons -->
        <div class="row g-4 my-3" id="action-button-container">
            <div class="col-3">
                <div class="d-flex flex-column align-items-center">
                    <a href="airtime.php" class="action-button">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 5C12.5523 5 13 4.55228 13 4C13 3.44772 12.5523 3 12 3C11.4477 3 11 3.44772 11 4C11 4.55228 11.4477 5 12 5ZM12 5L14.5 14M12 5L9.5 14M16 1C16 1 17.5 2 17.5 4C17.5 6 16 7 16 7M8 1C8 1 6.5 2 6.5 4C6.5 6 8 7 8 7M14.5 14H9.5M14.5 14L15.8889 19M9.5 14L8.11111 19M7 23L8.11111 19M8.11111 19H15.8889M15.8889 19L17 23"
                                stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <span class="text-secondary mt-2">Airtime</span>
                </div>
            </div>

            <div class="col-3">
                <div class="d-flex flex-column align-items-center">
                    <a href="data.php" class="action-button">
                        <!--  -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 19.51L12.01 19.4989M2 8C8 3.5 16 3.5 22 8M5 12C9 8.99999 15 9 19 12M8.5 15.5C10.7504 14.1 13.2498 14.0996 15.5001 15.5"
                                stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </a>
                    <span class="text-secondary mt-2">Data</span>
                </div>
            </div>

            <div class="col-3">
                <div class="d-flex flex-column align-items-center">
                    <a href="deposit.php" class="action-button">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19 20H5C3.89543 20 3 19.1046 3 18V9C3 7.89543 3.89543 7 5 7H19C20.1046 7 21 7.89543 21 9V18C21 19.1046 20.1046 20 19 20Z"
                                stroke="#94241E" stroke-width="1.5" />
                            <path
                                d="M16.5 14C16.2239 14 16 13.7761 16 13.5C16 13.2239 16.2239 13 16.5 13C16.7761 13 17 13.2239 17 13.5C17 13.7761 16.7761 14 16.5 14Z"
                                fill="#94241E" stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M18 7V5.60322C18 4.28916 16.7544 3.33217 15.4847 3.67075L4.48467 6.60409C3.60917 6.83756 3 7.63046 3 8.53656V9"
                                stroke="#94241E" stroke-width="1.5" />
                        </svg>

                    </a>
                    <span class="text-secondary mt-2">Deposit</span>
                </div>
            </div>

            <div class="col-3">
                <div class="d-flex flex-column align-items-center">
                    <a href="bills.php" class="action-button">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11 9L22 9" stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M2 11L4.80662 7.84255C5.5657 6.98859 6.65372 6.5 7.79627 6.5L8 6.5"
                                stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M2 19.5003L7.5 19.5L11.5 16.5003C11.5 16.5003 12.3091 15.9528 13.5 15.0001C16 13.0002 13.5 9.83352 11 11.4997C8.96409 12.8565 7 14.0003 7 14.0003"
                                stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M8 13.5V7C8 5.89543 8.89543 5 10 5H20C21.1046 5 22 5.89543 22 7V13C22 14.1046 21.1046 15 20 15H13.5"
                                stroke="#94241E" stroke-width="1.5" />
                        </svg>

                    </a>
                    <span class="text-secondary mt-2">Bills</span>
                </div>
            </div>
        </div>

        <!-- Transactions Section -->
        <div class="p-0 mt-5">
            <div class="text-white">
                <h6 class="mb-3">Recent Transactions</h6>
            </div>
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
                        $date = date("M d, Y H:m", strtotime($transaction['created_at']));
                        $prefix = ($transaction['transaction_type'] === 'Deposit') ? '+' : '-';

                        if ($transactions) {
                ?>
                <div class='d-flex justify-content-between align-items-start mb-3 pb-2'>
                    <div class='d-flex align-items-center gap-3'>
                        <div class='transaction-icon p-2'><?= $icon ?></div>
                        <div>
                            <h6 class='mb-0'><?= $transaction['transaction_type'] ?></h6>
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
            eyeIcon.innerHTML =
                '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
        } else {
            balanceAmount.classList.add('d-none');
            hiddenBalance.classList.remove('d-none');
            eyeIcon.innerHTML =
                '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/>';
        }
    });
    </script>
    <script>
    document.getElementById('copy-button').addEventListener('click', function() {
        const accountNumber = document.getElementById('account-number').innerText.trim();

        // Copy to clipboard
        navigator.clipboard.writeText(accountNumber).then(() => {
            const copyBtn = document.getElementById('copy-button');
            // Change icon to checkmark and color to green
            copyBtn.classList.remove('fa-copy');
            copyBtn.classList.add('fa-check', 'text-success');

            showToasted('Copied successfully', 'success');

            // Revert back after 2 seconds
            setTimeout(() => {
                copyBtn.classList.remove('fa-check', 'text-success');
                copyBtn.classList.add('fa-copy', 'text-primary');
            }, 3000);
        }).catch(err => {
            console.error('Copy failed: ', err);
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>