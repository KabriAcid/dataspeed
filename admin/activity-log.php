<?php
session_start();
// Auth guard
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
                <h1 class="page-title">Activity Log</h1>
                <p class="page-subtitle">View recent platform activities</p>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search activities...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="login">Login</option>
                                <option value="logout">Logout</option>
                                <option value="purchase">Purchase</option>
                                <option value="update_profile">Profile Update</option>
                                <option value="system">System</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="dateFilter">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary" onclick="loadLogs()">
                                <i class="ni ni-zoom-split-in me-2"></i>Search
                            </button>
                            <button class="btn btn-outline-secondary" onclick="exportLogs()">
                                <i class="ni ni-cloud-download-95 me-2"></i>Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logs Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th class="d-none d-lg-table-cell">Description</th>
                                    <th class="d-none d-md-table-cell">IP Address</th>
                                    <th class="text-nowrap">Date</th>
                                </tr>
                            </thead>
                            <tbody id="logsTableBody">
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
            bindFilters();
            loadLogs();
            window.addEventListener('pageChange', (e) => {
                currentPage = e.detail.page;
                loadLogs();
            });
        });

        function bindFilters() {
            const searchInput = document.getElementById('searchInput');
            const debounced = debounce(() => {
                currentPage = 1;
                loadLogs();
            }, 400);
            searchInput.addEventListener('input', debounced);
            ['typeFilter', 'dateFilter'].forEach(id => {
                document.getElementById(id).addEventListener('change', () => {
                    currentPage = 1;
                    loadLogs();
                });
            });
        }

        async function loadLogs() {
            const tbody = document.getElementById('logsTableBody');
            try {
                setLoadingState(tbody, true);
                const params = new URLSearchParams({
                    action: 'list',
                    page: String(currentPage),
                    per_page: '20',
                    search: document.getElementById('searchInput').value.trim(),
                    type: document.getElementById('typeFilter').value,
                    date: document.getElementById('dateFilter').value,
                });
                const res = await apiFetch('api/activity_log.php?' + params.toString());
                if (!res.success) throw new Error(res.message || 'Failed');
                renderLogs(res.data.items);
                renderPagination(document.getElementById('paginationContainer'), res.data.pagination);
            } catch (e) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">Error loading activity</td></tr>';
                showToasted('Error loading activity', 'error');
            } finally {
                setLoadingState(tbody, false);
            }
        }

        function renderLogs(items) {
            const tbody = document.getElementById('logsTableBody');
            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5"><div class="empty-state"><i class="ni ni-time-alarm"></i><h3>No activity found</h3><p>Try adjusting your filters</p></div></td></tr>';
                return;
            }
            tbody.innerHTML = items.map((log, i) => `
                <tr>
                    <td>${i + 1 + (currentPage - 1) * 20}</td>
                    <td class="min-w-0"><div class="text-truncate truncate-200" title="${log.username || 'Guest'}">${log.username || 'Guest'}</div></td>
                    <td><span class="badge bg-secondary">${log.action_type}</span></td>
                    <td class="d-none d-lg-table-cell min-w-0"><div class="text-truncate truncate-300" title="${log.action_description || ''}">${log.action_description || ''}</div></td>
                    <td class="d-none d-md-table-cell">${log.ip_address || '-'}</td>
                    <td class="text-nowrap">${formatDate(log.created_at)}</td>
                </tr>
            `).join('');
        }

        function exportLogs() {
            showToasted('Export coming soon', 'info');
        }
    </script>
</body>

</html>