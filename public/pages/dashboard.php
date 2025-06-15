<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/session-lock.php';
require __DIR__ . '/../partials/header.php';

$success = null;
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}

// if (isset($_SESSION['reauth_required']) && !empty($_SESSION['reauth_required'])) {
//     require __DIR__ . '/../partials/auth-modal.php';
//     echo "<script>document.addEventListener('DOMContentLoaded', function(){ showReauthModal(); });</script>";
//     exit;
// }
?>

<body>
    <main class="container-fluid py-4 mb-5">
        <?php
        if ($success == 1) {
            echo "<script>showToasted('Login Successful', 'success')</script>";
        }
        ?>
        <header class="d-flex justify-content-between align-items-center mb-4">
            <!-- Left Content -->
            <div class="d-flex align-items-center">
                <a href="profile.php">
                    <img src="<?= $user['photo'] ?>" alt="photo" class="profile-avatar">
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
        <?php
        $balanceChange = getRecentBalanceChangePercent($pdo, $user_id);
        ?>
        <div class="d-flex align-items-center">
            <h2 class="display-5 fw-bold mb-0 digit m-0" id="balanceAmount"><?= "&#8358;" . getUserBalance($pdo, $user_id) ?><span class="m-0"><?php if ($balanceChange['valid']): ?><small class="fw-bold text-xs <?= $balanceChange['direction'] === 'debit' ? 'text-danger' : 'text-success' ?>">
                            <?= ($balanceChange['percent'] > 0 ? ($balanceChange['direction'] === 'credit' ? '+' : '') : '') . $balanceChange['percent'] ?>%</small>
                    <?php endif; ?>
                </span></h2>
            <h2 class="display-5 fw-bold text-center d-none mb-0" id="hiddenBalance">*********</h2>
            <!-- <button class="btn btn-link text-secondary p-0 mx-1 py-0 my-0" id="toggleBalance" type="button">
                <span id="balanceEye">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 13C6.6 5 17.4 5 21 13M9 14C9 15.6569 10.3431 17 12 17C13.6569 17 15 15.6569 15 14C15 12.3431 13.6569 11 12 11C10.3431 11 9 12.3431 9 14Z"
                            stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </button> -->
        </div>
        <!--  -->


        <!-- TRANSACTION PIN NOT SET -->
        <?php
        if (!$user['txn_pin']) {
        ?>
            <div class="bg-white border-0 rounded shadow-xl px-4 py-3 my-4 animate-fade-in cursor-pointer" onclick="location.href='security-settings.php?tab=pin'">
                <div class="d-flex align-items-center">
                    <img src="../assets/img/icons/cyber-security.png" alt="" class="avatar-sm" style="max-width:50px;">
                    <div class="ms-4">
                        <h6 class="mb-0 text-dark fw-bold">Set Transaction PIN</h6>
                        <p class="text-secondary mb-0 text-md">Secure your transactions with a PIN.</p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <!-- KYC NOT MADE -->
        <?php
        if ($user['kyc_status'] == 'unverified') {
        ?>
            <div class="bg-white border-0 rounded shadow-xl px-4 py-3 my-4 animate-fade-in cursor-pointer" onclick="location.href='kyc.php'">
                <div class="d-flex align-items-center">
                    <img src="../assets/icons/cbn.svg" alt="" class="avatar-sm" style="max-width:50px;">
                    <div class="ms-4">
                        <h6 class="mb-0 text-dark fw-bold">Complete KYC</h6>
                        <p class="text-secondary mb-0 text-md">Verify your identity to unlock full features.</p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>

        <!-- ✅ Account Details Banner -->
        <?php if ($user): ?>
            <div class="account-banner bg-white border-0 rounded shadow-xl p-4 my-4 animate-fade-in">
                <h4 class="mb-4 text-dark fw-bold border-start border-4 border-danger ps-3">Bank Account Details</h4>
                <div class="row text-dark">
                    <div class="col-md-4 mb-3">
                        <h6 class="text-uppercase text-secondary mb-1 text-sm">Account Number</h6>
                        <div class="d-flex align-items-center">
                            <p class="fs-5 fw-bolder me-2 mb-0" id="account-number">
                                <?= htmlspecialchars($user['virtual_account']) ?>
                            </p>
                            <svg width="20" id="copy-icon" class="cursor-pointer" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.83333 6.61539C5.12206 6.61539 4.54545 7.18938 4.54545 7.89744V19.1795C4.54545 19.8875 5.12206 20.4615 5.83333 20.4615H14.0758C14.787 20.4615 15.3636 19.8875 15.3636 19.1795V11.3112C15.3636 10.9712 15.2279 10.6451 14.9864 10.4047L11.5571 6.99089C11.3156 6.75046 10.988 6.61539 10.6465 6.61539H5.83333ZM3 7.89744C3 6.33971 4.26853 5.07692 5.83333 5.07692H10.6465C11.3979 5.07692 12.1186 5.37408 12.6499 5.90303L16.0792 9.3168C16.6106 9.84575 16.9091 10.5632 16.9091 11.3112V19.1795C16.9091 20.7372 15.6406 22 14.0758 22H5.83333C4.26853 22 3 20.7372 3 19.1795V7.89744Z" fill="#030D45" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.12121 2.76923C7.12121 2.3444 7.46717 2 7.89394 2H12.1919C12.9434 2 13.664 2.29716 14.1954 2.82611L19.1701 7.77834C19.7015 8.30729 20 9.0247 20 9.77275V17.1282C20 17.553 19.654 17.8974 19.2273 17.8974C18.8005 17.8974 18.4545 17.553 18.4545 17.1282V9.77275C18.4545 9.43273 18.3189 9.10663 18.0773 8.8662L13.1026 3.91397C12.8611 3.67353 12.5335 3.53846 12.1919 3.53846H7.89394C7.46717 3.53846 7.12121 3.19407 7.12121 2.76923Z" fill="#030D45" />
                            </svg>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-uppercase text-secondary mb-1 text-sm">Account Name</h6>
                        <p class="fs-5 fw-bolder"><?= htmlspecialchars($user['account_name']) ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-uppercase text-secondary mb-1 text-sm">Bank Name</h6>
                        <p class="fs-5 fw-bolder"><?= htmlspecialchars($user['bank_name']) ?></p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning my-3">Unable to retrieve account details.</div>
        <?php endif; ?>


        <!-- Action Buttons -->
        <div class="row my-3 action-grid">
            <div class="col-3 text-center d-flex justify-content-center">
                <a href="buy_airtime.php" class="action-grid-btn d-flex flex-column align-items-center">
                    <span class="action-grid-icon mb-1">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5C12.5523 5 13 4.55228 13 4C13 3.44772 12.5523 3 12 3C11.4477 3 11 3.44772 11 4C11 4.55228 11.4477 5 12 5ZM12 5L14.5 14M12 5L9.5 14M16 1C16 1 17.5 2 17.5 4C17.5 6 16 7 16 7M8 1C8 1 6.5 2 6.5 4C6.5 6 8 7 8 7M14.5 14H9.5M14.5 14L15.8889 19M9.5 14L8.11111 19M7 23L8.11111 19M8.11111 19H15.8889M15.8889 19L17 23"
                                stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="action-grid-label">Airtime</span>
                </a>
            </div>
            <div class="col-3 text-center d-flex justify-content-center">
                <a href="buy_data.php" class="action-grid-btn d-flex flex-column align-items-center">
                    <span class="action-grid-icon mb-1">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <path d="M12 19.51L12.01 19.4989M2 8C8 3.5 16 3.5 22 8M5 12C9 8.99999 15 9 19 12M8.5 15.5C10.7504 14.1 13.2498 14.0996 15.5001 15.5"
                                stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="action-grid-label">Data</span>
                </a>
            </div>
            <div class="col-3 text-center d-flex justify-content-center">
                <a href="transfer_money.php" class="action-grid-btn d-flex flex-column align-items-center">
                    <span class="action-grid-icon mb-1">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="var(--primary)" stroke-width="1.5" />
                            <circle cx="12" cy="12" r="4" fill="var(--primary)" fill-opacity="0.12" />
                        </svg>
                    </span>
                    <span class="action-grid-label">Transfer</span>
                </a>
            </div>
            <div class="col-3 text-center d-flex justify-content-center">
                <a href="tv.php" class="action-grid-btn d-flex flex-column align-items-center">
                    <span class="action-grid-icon mb-1">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <rect x="4" y="7" width="16" height="10" rx="2" stroke="var(--primary)" stroke-width="1.5" />
                            <path d="M8 17V19M16 17V19" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </span>
                    <span class="action-grid-label">TV</span>
                </a>
            </div>
            <div class="col-3 text-center d-flex justify-content-center">
                <a href="electricity_bills.php" class="action-grid-btn d-flex flex-column align-items-center">
                    <span class="action-grid-icon mb-1">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <path d="M12 17C15.3137 17 18 14.3137 18 11C18 7.68629 15.3137 5 12 5C8.68629 5 6 7.68629 6 11C6 14.3137 8.68629 17 12 17Z"
                                stroke="var(--primary)" stroke-width="1.5" />
                            <circle cx="12" cy="11" r="3" fill="var(--primary)" fill-opacity="0.12" />
                        </svg>
                    </span>
                    <span class="action-grid-label">Electricity</span>
                </a>
            </div>
            <div class="col-3 text-center d-flex justify-content-center">
                <a href="loan.php" class="action-grid-btn d-flex flex-column align-items-center">
                    <span class="action-grid-icon mb-1">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <rect x="6" y="8" width="12" height="8" rx="2" stroke="var(--primary)" stroke-width="1.5" />
                            <path d="M12 12V16" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </span>
                    <span class="action-grid-label">Loan</span>
                </a>
            </div>
            <div class="col-3 text-center d-flex justify-content-center">
                <a href="referrals.php" class="action-grid-btn d-flex flex-column align-items-center">
                    <span class="action-grid-icon mb-1">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z"
                                stroke="var(--primary)" stroke-width="1.5" />
                            <path d="M2 20C2 16.6863 7.33333 15 12 15C16.6667 15 22 16.6863 22 20" stroke="var(--primary)" stroke-width="1.5" />
                        </svg>
                    </span>
                    <span class="action-grid-label">Referrals</span>
                </a>
            </div>
            <div class="col-3 text-center d-flex justify-content-center">
                <a href="more.php" class="action-grid-btn d-flex flex-column align-items-center">
                    <span class="action-grid-icon mb-1">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="2" fill="var(--primary)" />
                            <circle cx="12" cy="5" r="2" fill="var(--primary)" />
                            <circle cx="12" cy="19" r="2" fill="var(--primary)" />
                        </svg>
                    </span>
                    <span class="action-grid-label">More</span>
                </a>
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
                        $icon = "<i class='{$transaction['icon']} {$transaction['color']}'></i>";
                        $textColor = $transaction['direction'] === 'credit' ? 'text-success' : 'text-danger';
                        $formattedAmount = number_format($transaction['amount'], 2);
                        $date = date("h:i A . d F, Y.", strtotime($transaction['created_at']));
                        $prefix = $transaction['direction'] === 'credit' ? '+₦' : '-₦';

                        if ($transactions) {
                ?>
                            <div class='d-flex justify-content-between align-items-start mb-3 pb-2'>
                                <div class='d-flex align-items-center gap-3'>
                                    <div class='transaction-icon'><?= $icon ?></div>
                                    <div>
                                        <h6 class='mb-0 text-capitalize'><?= $transaction['type'] ?></h6>
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

        <!-- FontAwesome CDN -->
    </main>
    <script src="../assets/js/toggle-number.js"></script>
    <script>
        document.getElementById('copy-icon').addEventListener('click', function() {
            const referralCode = document.getElementById('account-number').innerText.trim();

            // Copy to clipboard
            navigator.clipboard.writeText(referralCode).then(() => {
                const copyBtn = document.getElementById('copy-icon');
                // Change icon to checkmark and color to green

                showToasted('Copied successfully', 'success');

                document.getElementById('copy-icon').innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.9989 11.2858V19.1429C16.9989 20.6735 15.7637 22 14.1168 22H5.88213C4.33813 22 3 20.7756 3 19.1429V7.91841C3 6.3878 4.2352 5.06127 5.88213 5.06127H10.72C11.4405 5.06127 12.1611 5.36739 12.7787 5.8776L16.1755 9.24495C16.6901 9.85719 16.9989 10.5715 16.9989 11.2858Z" fill="#030D45"/>
                <path d="M19.2635 17.9184C18.8517 17.9184 18.44 17.6123 18.44 17.1021V9.75515C18.44 9.44903 18.3371 9.1429 18.0283 8.83678L13.0875 3.93882C12.8816 3.73474 12.4699 3.53066 12.1611 3.53066H7.9408C7.52907 3.6327 7.11733 3.22454 7.11733 2.81637C7.11733 2.40821 7.52907 2.00005 7.9408 2.00005H12.264C12.9845 2.00005 13.7051 2.30617 14.2197 2.81637L19.1605 7.71433C19.6752 8.22454 19.984 8.93882 19.984 9.65311V17.1021C20.0869 17.5103 19.6752 17.9184 19.2635 17.9184Z" fill="#030D45"/>
                </svg>
            `;

                setTimeout(() => {
                    document.getElementById('copy-icon').innerHTML = `
                    <svg width="24" id="copy-icon" class="cursor-pointer" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.83333 6.61539C5.12206 6.61539 4.54545 7.18938 4.54545 7.89744V19.1795C4.54545 19.8875 5.12206 20.4615 5.83333 20.4615H14.0758C14.787 20.4615 15.3636 19.8875 15.3636 19.1795V11.3112C15.3636 10.9712 15.2279 10.6451 14.9864 10.4047L11.5571 6.99089C11.3156 6.75046 10.988 6.61539 10.6465 6.61539H5.83333ZM3 7.89744C3 6.33971 4.26853 5.07692 5.83333 5.07692H10.6465C11.3979 5.07692 12.1186 5.37408 12.6499 5.90303L16.0792 9.3168C16.6106 9.84575 16.9091 10.5632 16.9091 11.3112V19.1795C16.9091 20.7372 15.6406 22 14.0758 22H5.83333C4.26853 22 3 20.7372 3 19.1795V7.89744Z" fill="#030D45"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.12121 2.76923C7.12121 2.3444 7.46717 2 7.89394 2H12.1919C12.9434 2 13.664 2.29716 14.1954 2.82611L19.1701 7.77834C19.7015 8.30729 20 9.0247 20 9.77275V17.1282C20 17.553 19.654 17.8974 19.2273 17.8974C18.8005 17.8974 18.4545 17.553 18.4545 17.1282V9.77275C18.4545 9.43273 18.3189 9.10663 18.0773 8.8662L13.1026 3.91397C12.8611 3.67353 12.5335 3.53846 12.1919 3.53846H7.89394C7.46717 3.53846 7.12121 3.19407 7.12121 2.76923Z" fill="#030D45"/>
                    </svg>
                `;
                }, 2000);

            }).catch(err => {
                showToasted('Could not copy code', 'error');
                console.error('Copy failed: ', err);
            });
        });
    </script>
    <script>
        window.addEventListener("load", () => {
            // Create a new URL object based on the current location
            const url = new URL(window.location);

            // Remove 'success' parameter from the URL
            url.searchParams.delete("success");

            // Update the URL in the browser without reloading
            window.history.replaceState(null, null, url.pathname + url.search);
        });
    </script>
    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php';?>
</body>

</html>