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
                <h1 class="page-title">Transactions</h1>
                <p class="page-subtitle">Monitor and manage all platform transactions</p>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-info">
                                <i class="ni ni-money-coins"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="totalVolume">₦0</h3>
                                <p class="stat-label">Total Volume</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Completed -->
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-success">
                                <i class="ni ni-check-bold"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="completedCount">-</h3>
                                <p class="stat-label">Completed</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pending -->
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-warning">
                                <i class="ni ni-time-alarm"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="pendingCount">-</h3>
                                <p class="stat-label">Pending</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Failed -->
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-danger">
                                <i class="ni ni-fat-remove"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="failedCount">-</h3>
                                <p class="stat-label">Failed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search transactions...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="data">Data</option>
                                <option value="airtime">Airtime</option>
                                <option value="electricity">Electricity</option>
                                <option value="cable_tv">Cable TV</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="dateFilter">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary" onclick="loadTransactions()">
                                Search
                            </button>
                            <button class="btn btn-outline-secondary" onclick="exportTransactions()">
                                <i class="ni ni-cloud-download-95 me-2"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Transactions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th class="d-none d-lg-table-cell">User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="d-none d-md-table-cell">Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="transactionsTableBody">
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div id="paginationContainer" class="mt-4"></div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>

    <script>
        let currentPage = 1;

        document.addEventListener('DOMContentLoaded', function() {
            topbarInit();
            loadTransactions();
            loadTransactionStats();

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const debouncedSearch = debounce(() => {
                currentPage = 1;
                loadTransactions();
            }, 500);

            searchInput.addEventListener('input', debouncedSearch);

            // Filter changes
            ['statusFilter', 'typeFilter', 'dateFilter'].forEach(id => {
                document.getElementById(id).addEventListener('change', () => {
                    currentPage = 1;
                    loadTransactions();
                });
            });

            // Pagination
            window.addEventListener('pageChange', (e) => {
                currentPage = e.detail.page;
                loadTransactions();
            });
        });

        async function loadTransactionStats() {
            try {
                const res = await apiFetch('api/transactions.php?action=stats');
                if (res.success) {
                    const {
                        completed,
                        pending,
                        failed,
                        total_volume
                    } = res.data;
                    document.getElementById('completedCount').textContent = (completed || 0).toLocaleString();
                    document.getElementById('pendingCount').textContent = (pending || 0).toLocaleString();
                    document.getElementById('failedCount').textContent = (failed || 0).toLocaleString();
                    document.getElementById('totalVolume').textContent = formatCurrency(total_volume || 0);
                }
            } catch (error) {
                console.error('Failed to load transaction stats:', error);
            }
        }

        async function loadTransactions() {
            const tableBody = document.getElementById('transactionsTableBody');

            try {
                setLoadingState(tableBody, true);
                const params = new URLSearchParams({
                    page: String(currentPage),
                    per_page: '20',
                    search: document.getElementById('searchInput').value.trim(),
                    status: document.getElementById('statusFilter').value,
                    type: document.getElementById('typeFilter').value,
                    date: document.getElementById('dateFilter').value
                });
                const res = await apiFetch('api/transactions.php?action=list&' + params.toString());
                if (!res.success) throw new Error(res.message || 'Failed');
                const {
                    items,
                    pagination
                } = res.data;
                renderTransactionsTable(items);
                renderPagination(document.getElementById('paginationContainer'), pagination);
            } catch (error) {
                showToasted('Error loading transactions', 'error');
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4">Error loading transactions</td></tr>';
            } finally {
                setLoadingState(tableBody, false);
            }
        }

        // removed mock generator; using API
        function renderTransactionsTable(transactions) {
            const tableBody = document.getElementById('transactionsTableBody');

            if (transactions.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="ni ni-credit-card"></i>
                                <h3>No transactions found</h3>
                                <p>Try adjusting your search criteria</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = transactions.map(transaction => `
                <tr>
                    <td>
                        <div class="fw-semibold">${transaction.id}</div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <div class="text-truncate truncate-200" title="${transaction.user_name}">${transaction.user_name}</div>
                    </td>
                    <td>
                        <span class="badge bg-${getTypeColor(transaction.service_type)}">${transaction.service_type}</span>
                    </td>
                    <td>
                        <span class="fw-semibold">${formatCurrency(transaction.amount)}</span>
                    </td>
                    <td>
                        <span class="badge ${getStatusBadgeClass(transaction.status)}">${transaction.status}</span>
                    </td>
                    <td class="d-none d-md-table-cell text-nowrap">
                        <div>${formatDate(transaction.created_at)}</div>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="viewTransaction('${transaction.id}')" title="View Details">
                                <i class="ni ni-zoom-split-in"></i>
                            </button>
                            <button class="btn btn-outline-success" onclick="approveTransaction('${transaction.id}')" title="Approve">
                                <i class="ni ni-check-bold"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="rejectTransaction('${transaction.id}')" title="Reject">
                                <i class="ni ni-fat-remove"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function getTypeColor(type) {
            const colors = {
                'data': 'primary',
                'airtime': 'success',
                'electricity': 'warning',
                'cable_tv': 'info'
            };
            return colors[type] || 'secondary';
        }

        function getStatusBadgeClass(status) {
            const classes = {
                'completed': 'bg-success',
                'pending': 'bg-warning',
                'failed': 'bg-danger'
            };
            return classes[status] || 'bg-secondary';
        }

        function viewTransaction(transactionId) {
            showToasted(`Viewing transaction ${transactionId}`, 'info');
            // Implement transaction details modal
        }

        function approveTransaction(transactionId) {
            if (confirm('Are you sure you want to approve this transaction?')) {
                showToasted(`Transaction ${transactionId} approved`, 'success');
                loadTransactions();
            }
        }

        function rejectTransaction(transactionId) {
            if (confirm('Are you sure you want to reject this transaction?')) {
                showToasted(`Transaction ${transactionId} rejected`, 'error');
                loadTransactions();
            }
        }

        function exportTransactions() {
            showToasted('Export functionality coming soon', 'info');
        }
    </script>
</body>

</html>