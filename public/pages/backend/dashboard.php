<?php
require __DIR__ . '/../../partials/header.php';
require __DIR__ . '/../../../config/config.php'; // Ensure database connection is included
session_start();

// Check if user session exists
if (isset($_SESSION['user']) && isset($_SESSION['user']['first_name'])) {
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
                <p class="text-secondary">Kabri Acid</p>
            </div>
            <div class="profile-avatar">
                <span>A</span>
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
                <h2 class="display-5 fw-bold text-center " id="balanceAmount">$2,850.75</h2>
                <h2 class="display-5 fw-bold text-center d-none" id="hiddenBalance">••••••</h2>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row g-4 mb-4">
            <div class="col-3">
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
            <div class="col-3">
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
            <div class="col-3">
                <div class="d-flex flex-column align-items-center">
                    <a class="action-button bg-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2h0V5z" />
                            <path d="M2 9v1c0 1.1.9 2 2 2h1" />
                            <path d="M16 5h0" />
                        </svg>
                    </a>
                    <span class="text-secondary mt-2">Invest</span>
                </div>
            </div>
            <div class="col-3">
                <div class="d-flex flex-column align-items-center">
                    <button class="action-button bg-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                    </button>
                    <span class="text-secondary mt-2">Add</span>
                </div>
            </div>
            <div class="col-3">
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
            <div class="col-3">
                <div class="d-flex flex-column align-items-center">
                    <button class="action-button" style="background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" />
                        </svg>
                    </button>
                    <span class="text-secondary mt-2">Donate</span>
                </div>
            </div>
            <div class="col-3">
                <div class="d-flex flex-column align-items-center">
                    <button class="action-button bg-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20" />
                        </svg>
                    </button>
                    <span class="text-secondary mt-2">Bills</span>
                </div>
            </div>
            <div class="col-3">
                <div class="d-flex flex-column align-items-center">
                    <button class="action-button" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3" />
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1Z" />
                        </svg>
                    </button>
                    <span class="text-secondary mt-2">Settings</span>
                </div>
            </div>
        </div>

        <!-- Transactions Section -->
        <div class="card bg-dark-subtle p-0">
            <div class="card-header">
                <h3 class=" fs-4 fw-semibold mb-4">Recent Transactions</h3>
            </div>
            <div class="card-body">
                <div class="transaction-list">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="transaction-icon bg-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m22 4-10.5 7L22 18" />
                                    <path d="M2 4v16" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-0 ">Airtime Purchase</p>
                                <p class="text-secondary small mb-0">Mar 15, 2024</p>
                            </div>
                        </div>
                        <span class="text-danger fw-semibold">- $11.99</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="transaction-icon bg-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 7H2" />
                                    <circle cx="12" cy="12" r="3" />
                                    <path d="M22 12H2" />
                                    <path d="M22 17H2" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-0 ">Electricity Bills</p>
                                <p class="text-secondary small mb-0">Mar 14, 2024</p>
                            </div>
                        </div>
                        <span class="text-danger fw-semibold">-$15.00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="transaction-icon bg-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v20" />
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-0 ">Payment Deposit</p>
                                <p class="text-secondary small mb-0">Mar 13, 2024</p>
                            </div>
                        </div>
                        <span class="text-success fw-semibold">+$1,250.00</span>
                    </div>
                </div>
            </div>
        </div>
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
    <script src="assets/js/auth.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>