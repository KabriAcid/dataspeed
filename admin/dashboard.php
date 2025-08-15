<?php
session_start();

// Check authentication
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/header.php';
?>

<body class="admin-body">
    <?php include 'includes/topbar.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Welcome back! Here's what's happening with your platform.</p>
            </div>

            <!-- Provider Balances -->
            <div class="row g-4 mb-4">
                <div class="col-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon">
                                <img src="../public/assets/img/brands/ebills_favicon.webp" alt="" class="img-fluid brand-icons">
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="ebillsBalance">₦0.00</h3>
                                <p class="stat-label">eBills Balance</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon">
                                <img src="../public/assets/img/brands/billstack_favicon.avif" alt="" class="img-fluid brand-icons">
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="billstackBalance">—</h3>
                                <p class="stat-label">Billstack Balance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="row g-4 mb-4" id="kpiCards">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-primary">
                                <i class="ni ni-single-02"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="activeUsers">-</h3>
                                <p class="stat-label">Active Users</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-success">
                                <i class="ni ni-money-coins"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="todayRevenue">₦0</h3>
                                <p class="stat-label">Today's Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-info">
                                <i class="ni ni-circle-08"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="newUsersToday">-</h3>
                                <p class="stat-label">New Users Today</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-warning">
                                <i class="ni ni-wallet-43"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="totalBalance">₦0</h3>
                                <p class="stat-label">Total Users' Balance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Daily Transactions</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" id="dailyChart">
                                <div class="chart-placeholder">
                                    <i class="ni ni-chart-bar-32"></i>
                                    <p>Loading chart data...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Bill Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" id="billChart">
                                <div class="chart-placeholder">
                                    <i class="ni ni-chart-pie-35"></i>
                                    <p>Loading chart data...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>
    <!-- Chart.js (page-scoped) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- Dashboard Charts JS -->
    <script src="assets/js/dashboard-charts.js"></script>
</body>

</html>