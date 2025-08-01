<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container-fluid py-4 mb-5">
        <?php
        if (isset($_GET['success'])) {
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
                    <h4 class="fs-4 fw-bold mb-0 text-capitalize">
                        <?= !empty($user['user_name']) ? $user['user_name'] : $user['first_name']; ?>
                    </h4>

                    <p class="text-secondary text-sm m-0">Welcome back,</p>
                </div>
            </div>

            <!-- Right Content: Notification -->
            <div class="notification-icon">
                <a href="notifications.php">
                    <i class="ni ni-bell-55 fs-5 text-gradient text-dark"></i>
                    <span class="notification-badge"></span>
                </a>
            </div>
        </header>

        <!-- Balance Section -->
        <?php
        $wallet_balance = getUserBalance($pdo, $user_id);
        ?>
        <div class="d-flex align-items-center">
            <h1 class="display-5 fw-bold text-center mb-0" id="balanceAmount"><?= "&#8358;" . getUserBalance($pdo, $user_id) ?></h1>
            <h2 class="display-5 fw-bold text-center d-none mb-0" id="hiddenBalance">*********</h2>
            <button class="btn btn-link text-secondary p-0 mx-1 py-0 my-0" id="toggleBalance" type="button">
                <span id="balanceEye">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 13C6.6 5 17.4 5 21 13M9 14C9 15.6569 10.3431 17 12 17C13.6569 17 15 15.6569 15 14C15 12.3431 13.6569 11 12 11C10.3431 11 9 12.3431 9 14Z"
                            stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </button>
            <button class="btn btn-link text-secondary p-0 mx-1 py-0 my-0" id="refresh-balance" type="button" title="Refresh Balance">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.6506 17.6506C16.1679 19.1333 14.1175 20 12 20C7.58172 20 4 16.4183 4 12C4 7.58172 7.58172 4 12 4C16.4183 4 20 7.58172 20 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M17 8L20 12L17 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        <!--  -->


        <!-- TRANSACTION PIN NOT SET -->
        <?php
        if ($user && empty($user['txn_pin'])) {

        ?>
            <div class="bg-white border-0 rounded shadow-xl px-4 py-3 my-4 animate-fade-in cursor-pointer" onclick="location.href='password_pin_setting.php?tab=pin'">
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
        if ($user && isset($user['kyc_status']) && $user['kyc_status'] == 'unverified') {
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
                <a href="tv_subscription.php" class="action-grid-btn d-flex flex-column align-items-center">
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
                    <span class="action-grid-label">Withdraw</span>
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
                <a href="airtime-2-cash.php" class="action-grid-btn d-flex flex-column align-items-center">
                    <span class="action-grid-icon mb-1">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="2" fill="var(--primary)" />
                            <circle cx="12" cy="5" r="2" fill="var(--primary)" />
                            <circle cx="12" cy="19" r="2" fill="var(--primary)" />
                        </svg>
                    </span>
                    <span class="action-grid-label">Airtime2Cash</span>
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
                            <div class='d-flex justify-content-between align-items-top mb-3 pb-2'>
                                <div class='d-flex align-items-top'>
                                    <div class='transaction-icon'><?= $icon ?></div>
                                    <div class="mx-3">
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

        <audio id="balanceUpdateSound" src="../assets/sounds/ding.mp3"></audio>

        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>

        <!-- FontAwesome CDN -->
    </main>
    <script src="../assets/js/toggle-number.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const balanceAmount = document.getElementById("balanceAmount");
            const balanceUpdateSound = document.getElementById("balanceUpdateSound");
            const refreshBtn = document.getElementById("refresh-balance");
            let lastKnownBalance = null;
            let balanceCheckInterval = null;
            let isRefreshing = false;

            function animateBalanceUpdate(currentBalance, newBalance) {
                const balanceElement = document.getElementById("balanceAmount");
                const duration = 1000; // Animation duration in milliseconds
                const frameRate = 60; // Frames per second
                const totalFrames = Math.round(duration / (1000 / frameRate));
                const increment = (newBalance - currentBalance) / totalFrames;

                let frame = 0;
                const animation = setInterval(() => {
                    frame++;
                    const updatedBalance = currentBalance + increment * frame;
                    balanceElement.innerHTML = `&#8358;${updatedBalance.toFixed(2)}`;

                    if (frame === totalFrames) {
                        clearInterval(animation);
                    }
                }, 1000 / frameRate);
            }

            function updateBalance(isManualRefresh = false) {
                // Show loading state for manual refresh
                if (isManualRefresh) {
                    isRefreshing = true;
                    refreshBtn.style.animation = 'spin 1s linear infinite';
                    refreshBtn.style.opacity = '0.7';
                }

                fetch("update-balance.php", {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                        },
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            const currentBalance = parseFloat(
                                document.getElementById("balanceAmount").innerText.replace("₦", "").replace(",", "")
                            );
                            const newBalance = parseFloat(data.balance);

                            // Only animate and play sound if balance actually changed
                            if (lastKnownBalance !== null && newBalance !== currentBalance) {
                                animateBalanceUpdate(currentBalance, newBalance);
                                balanceUpdateSound.play().catch(e => console.log('Sound play failed:', e));

                                // Show notification for balance increase
                                if (newBalance > currentBalance) {
                                    const increase = newBalance - currentBalance;
                                    showToasted(`Balance updated! +₦${increase.toFixed(2)}`, 'success');
                                } else if (newBalance < currentBalance) {
                                    const decrease = currentBalance - newBalance;
                                    showToasted(`Balance updated! -₦${decrease.toFixed(2)}`, 'info');
                                }
                            } else if (lastKnownBalance === null) {
                                // First load, just update without animation
                                document.getElementById("balanceAmount").innerHTML = `&#8358;${newBalance.toFixed(2)}`;
                            } else if (isManualRefresh) {
                                // Manual refresh but no change
                                showToasted('Balance is up to date', 'info');
                            }

                            lastKnownBalance = newBalance;
                        } else {
                            console.error('Balance update failed:', data.message);
                            if (isManualRefresh) {
                                showToasted('Failed to refresh balance', 'error');
                            }
                        }
                    })
                    .catch((error) => {
                        console.error("Error updating balance:", error);
                        if (isManualRefresh) {
                            showToasted('Network error. Please try again.', 'error');
                        }
                    })
                    .finally(() => {
                        // Reset refresh button state
                        if (isManualRefresh) {
                            setTimeout(() => {
                                isRefreshing = false;
                                refreshBtn.style.animation = '';
                                refreshBtn.style.opacity = '';
                            }, 500);
                        }
                    });
            }

            // Get initial balance
            const initialBalance = parseFloat(
                document.getElementById("balanceAmount").innerText.replace("₦", "").replace(",", "")
            );
            lastKnownBalance = initialBalance;

            // Refresh button click handler
            refreshBtn.addEventListener('click', function() {
                if (!isRefreshing) {
                    updateBalance(true);
                }
            });

            // Start auto-checking for balance updates every 5 seconds
            function startBalanceMonitoring() {
                // Clear any existing interval
                if (balanceCheckInterval) {
                    clearInterval(balanceCheckInterval);
                }

                // Set up new interval
                balanceCheckInterval = setInterval(() => {
                    // Only check if user is on dashboard and tab is active
                    if (document.visibilityState === 'visible' && !isRefreshing) {
                        updateBalance(false);
                    }
                }, 5000); // Check every 5 seconds

                console.log('Balance monitoring started - checking every 5 seconds');
            }

            // Stop monitoring when user leaves the page
            function stopBalanceMonitoring() {
                if (balanceCheckInterval) {
                    clearInterval(balanceCheckInterval);
                    balanceCheckInterval = null;
                    console.log('Balance monitoring stopped');
                }
            }

            // Handle page visibility changes
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    // Page became visible, resume monitoring
                    startBalanceMonitoring();
                    // Check immediately when returning to page
                    setTimeout(() => updateBalance(false), 1000);
                } else {
                    // Page hidden, stop monitoring to save resources
                    stopBalanceMonitoring();
                }
            });

            // Handle window focus/blur for additional optimization
            window.addEventListener('focus', function() {
                if (!balanceCheckInterval) {
                    startBalanceMonitoring();
                }
                // Check immediately when window gains focus
                setTimeout(() => updateBalance(false), 500);
            });

            window.addEventListener('blur', function() {
                // Optional: reduce frequency when window loses focus
                // or keep running for immediate updates
            });

            // Start monitoring immediately
            startBalanceMonitoring();

            // Clean up on page unload
            window.addEventListener('beforeunload', function() {
                stopBalanceMonitoring();
            });
        });


        window.addEventListener("load", () => {
            // Create a new URL object based on the current location
            const url = new URL(window.location);

            // Remove 'success' parameter from the URL
            url.searchParams.delete("success");

            // Update the URL in the browser without reloading
            window.history.replaceState(null, null, url.pathname + url.search);
        });
    </script>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>

</body>

</html>