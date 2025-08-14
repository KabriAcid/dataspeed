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

    <?php include 'includes/scripts.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            topbarInit();
            loadDashboardStats();
        });

        async function loadDashboardStats() {
            try {
                const response = await apiFetch('api/stats.php');
                if (response.success) {
                    const data = response.data;

                    // Update KPI cards
                    document.getElementById('activeUsers').textContent = data.active_users || 0;
                    document.getElementById('todayRevenue').textContent = formatCurrency(data.today_revenue_amount || 0);
                    document.getElementById('newUsersToday').textContent = data.new_users_today || 0;
                    document.getElementById('totalBalance').textContent = formatCurrency(data.total_users_balance || 0);

                    // Simple chart rendering (CSS-based bars)
                    if (data.series && data.series.daily_transactions) {
                        renderDailyChart(data.series.daily_transactions);
                    }

                    if (data.series && data.series.bill_distribution) {
                        renderBillChart(data.series.bill_distribution);
                    }
                }
            } catch (error) {a
                console.error('Failed to load dashboard stats:', error);
            }
        }

        function renderDailyChart(data) {
            const container = document.getElementById('dailyChart');
            const maxValue = Math.max(...data.map(d => d.amount));

            container.innerHTML = `
                <div class="simple-chart">
                    ${data.map(d => `
                        <div class="chart-bar">
                            <div class="bar" style="height: ${(d.amount / maxValue) * 100}%"></div>
                            <span class="bar-label">${d.date}</span>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function renderBillChart(data) {
            const container = document.getElementById('billChart');
            const total = data.reduce((sum, d) => sum + d.value, 0);

            container.innerHTML = `
                <div class="bill-distribution">
                    ${data.map(d => `
                        <div class="bill-item">
                            <div class="bill-color" style="background-color: ${getBillColor(d.label)}"></div>
                            <span class="bill-label">${d.label}</span>
                            <span class="bill-value">${((d.value / total) * 100).toFixed(1)}%</span>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function getBillColor(label) {
            const colors = {
                'Data': 'var(--primary)',
                'Airtime': 'var(--warning)',
                'Bills payment': 'var(--info)',
                'Electricity': 'var(--success)'
            };
            return colors[label] || 'var(--gray-400)';
        }
    </script>
</body>

</html>