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
                <h1 class="page-title">Pricing Management</h1>
                <p class="page-subtitle">Manage service plans and pricing</p>
            </div>

            <!-- KPI Cards -->
            <div class="row g-4 mb-4" id="kpiCards">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-info">
                                <i class="ni ni-bullet-list-67"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="airtimePercentage">0%</h3>
                                <p class="stat-label">Airtime Markup</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-primary">
                                <i class="ni ni-chart-bar-32"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="dataPercentage">0%</h3>
                                <p class="stat-label">Data Markup</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-success">
                                <i class="ni ni-tv-2"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="tvPercentage">-</h3>
                                <p class="stat-label">TV Markup</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon bg-warning">
                                <i class="ni ni-money-coins"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-value" id="suggestedPricing">₦0</h3>
                                <p class="stat-label">Suggested Pricing</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Markup Cards -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">TV Markup</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <p class="mb-0">Global markup percentage for cable TV plans</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="tvMarkup" placeholder="0" min="0" max="100" step="0.1">
                                        <span class="input-group-text">%</span>
                                        <button class="btn btn-primary" onclick="updateTvMarkup()">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Data Markup</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <p class="mb-0">Global markup percentage for data plans</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="dataMarkup" placeholder="0" min="0" max="100" step="0.1">
                                        <span class="input-group-text">%</span>
                                        <button class="btn btn-primary" onclick="updateDataMarkup()">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Plans -->
            <div class="card mb-4">
                <div class="card-header mb-0">
                    <h6 class="mb-0">Service Plans</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
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
                            <button class="btn btn-outline-primary" onclick="loadStatsAndTable()">
                                Search
                            </button>
                            <button class="btn btn-outline-secondary" onclick="exportData()">
                                <i class="ni ni-cloud-download-95 me-2"></i>Export
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Network</th>
                                    <th>Data Size</th>
                                    <th>Validity</th>
                                    <th>Price (₦)</th>
                                    <th>Base Price (₦)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="plansTableBody">
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
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Plan Modal -->
    <div class="modal fade" id="planModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="planForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="planId">

                        <div class="mb-3">
                            <label for="planName" class="form-label">Plan Name</label>
                            <input type="text" class="form-control" name="name" id="planName" required>
                        </div>

                        <div class="mb-3">
                            <label for="planNetwork" class="form-label">Network</label>
                            <select class="form-select" name="network" id="planNetwork" required>
                                <option value="">Select Network</option>
                                <option value="MTN">MTN</option>
                                <option value="Airtel">Airtel</option>
                                <option value="Glo">Glo</option>
                                <option value="9mobile">9mobile</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="planCode" class="form-label">Plan Code</label>
                            <input type="text" class="form-control" name="code" id="planCode" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dataSize" class="form-label">Data Size</label>
                                    <input type="text" class="form-control" name="data_size" id="dataSize" placeholder="e.g., 1GB">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validity" class="form-label">Validity</label>
                                    <input type="text" class="form-control" name="validity" id="validity" placeholder="e.g., 30 days">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="planPrice" class="form-label">Base (retail) Price (₦)</label>
                                    <input type="number" class="form-control" name="base_price" id="planPrice" required min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="planStatus" class="form-label">Status</label>
                                    <select class="form-select" name="status" id="planStatus">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>

    <script>
        // Cache service plans for client-side filtering/export
        let PLANS_CACHE = [];
        document.addEventListener('DOMContentLoaded', function() {
            topbarInit();
            loadPricingData();
            loadKpis();
        });

        async function loadKpis() {
            try {
                // Show markup settings as KPI values
                const res = await apiFetch('api/pricing.php?action=plans');
                if (res && res.success && res.data) {
                    const ap = document.getElementById('airtimePercentage');
                    const dp = document.getElementById('dataPercentage');
                    const tv = document.getElementById('tvPercentage');
                    const airtime_markup = parseFloat(res.data.airtime_markup || 0) || 0;
                    const data_markup = parseFloat(res.data.data_markup || 0) || 0;
                    const tv_markup = parseFloat(res.data.tv_markup || 0) || 0;
                    if (ap) ap.textContent = airtime_markup + '%';
                    if (dp) dp.textContent = data_markup + '%';
                    if (tv) tv.textContent = tv_markup + '%';
                }
            } catch (e) {
                // Silent fail; KPI is optional
            }
        }

        async function loadPricingData() {
            const tableBody = document.getElementById('plansTableBody');

            try {
                setLoadingState(tableBody, true);

                const response = await apiFetch('api/pricing.php?action=plans');

                if (response.success) {
                    const {
                        plans,
                        airtime_markup,
                        data_markup,
                        tv_markup
                    } = response.data;

                    // cache
                    PLANS_CACHE = Array.isArray(plans) ? plans : [];

                    // Update TV and Data markup fields
                    const tvMarkupEl = document.getElementById('tvMarkup');
                    if (tvMarkupEl) tvMarkupEl.value = tv_markup;
                    const dataMarkupEl = document.getElementById('dataMarkup');
                    if (dataMarkupEl) dataMarkupEl.value = data_markup;

                    // Render with current filters
                    applyPlanFiltersAndRender();
                } else {
                    showToasted('Failed to load pricing data', 'error');
                }

            } catch (error) {
                showToasted('Error loading pricing data', 'error');
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4">Error loading data</td></tr>';
            } finally {
                setLoadingState(tableBody, false);
            }
        }

        // Apply filters from inputs to PLANS_CACHE and render
        function applyPlanFiltersAndRender() {
            const search = (document.getElementById('searchInput').value || '').trim().toLowerCase();
            const status = (document.getElementById('statusFilter').value || '').toLowerCase();
            const type = (document.getElementById('typeFilter').value || '').toLowerCase();
            const date = document.getElementById('dateFilter').value || '';

            let filtered = PLANS_CACHE.slice();

            if (search) {
                filtered = filtered.filter(p =>
                    (p.name || '').toLowerCase().includes(search) ||
                    (p.code || '').toLowerCase().includes(search) ||
                    (p.network || '').toLowerCase().includes(search)
                );
            }

            if (status) {
                filtered = filtered.filter(p => (p.status || '').toLowerCase() === status);
            }

            // Optional type/date hooks if you later map them for plans
            // Currently no-ops to keep UI stable
            // if (type) { /* implement when a plan "type" exists */ }
            // if (date) { /* implement if you add created_at range filtering */ }

            renderPlansTable(filtered);

            // Simple KPI: suggested pricing as avg price of filtered
            if (filtered.length > 0) {
                const avg = filtered.reduce((s, p) => s + (parseFloat(p.price) || 0), 0) / filtered.length;
                document.getElementById('suggestedPricing').textContent = '₦' + Math.round(avg).toLocaleString();
            }
        }

        // Backward compatible wrappers for your provided snippet
        function loadStatsAndTable() {
            // Ensure data is loaded, then apply filters
            if (!PLANS_CACHE.length) {
                loadPricingData();
            } else {
                applyPlanFiltersAndRender();
            }
        }

        function exportData() {
            // Export currently filtered table rows to CSV
            const table = document.querySelector('table');
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            const header = ['Plan Name', 'Network', 'Data Size', 'Validity', 'Base Price (NGN)', 'Price (NGN)', 'Status'];
            const data = rows.map(tr => Array.from(tr.querySelectorAll('td')).slice(0, 7).map(td => td.innerText.replace(/\s+/g, ' ').trim()));
            const csv = [header].concat(data).map(r => r.map(v => '"' + (v || '').replace(/"/g, '""') + '"').join(',')).join('\r\n');
            const blob = new Blob([csv], {
                type: 'text/csv;charset=utf-8;'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'service-plans-export.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        function renderPlansTable(plans) {
            const tableBody = document.getElementById('plansTableBody');

            if (plans.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="ni ni-money-coins"></i>
                                <h3>No plans found</h3>
                                <p>No service plans configured yet</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            // Group plans by network
            const groupedPlans = plans.reduce((acc, plan) => {
                if (!acc[plan.network]) {
                    acc[plan.network] = [];
                }
                acc[plan.network].push(plan);
                return acc;
            }, {});

            let html = '';

            Object.keys(groupedPlans).forEach(network => {
                const networkPlans = groupedPlans[network];

                networkPlans.forEach((plan, index) => {
                    html += `
                        <tr>
                            <td class="min-w-0">
                                <div class="fw-semibold text-truncate truncate-200" title="${plan.name}">${plan.name}</div>
                                <small class="text-muted">Code: ${plan.code}</small>
                            </td>
                            <td>
                                <span class="badge bg-${getNetworkColor(plan.network)}">${plan.network}</span>
                            </td>
                            <td>${plan.data_size || '-'}</td>
                            <td>${plan.validity || '-'}</td>
                            <td>
                                <span>₦${parseFloat(plan.price).toLocaleString()}</span>
                            </td>
                            <td class="fw-semibold" style="color: var(--primary)">
                                ₦${(plan.base_price != null ? parseFloat(plan.base_price) : 0).toLocaleString()}
                            </td>
                            <td>
                                <span class="badge ${plan.status === 'active' ? 'bg-success' : 'bg-secondary'}">${plan.status}</span>
                            </td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" onclick="editPlan(${plan.id})" title="Edit Plan">
                                    <i class="ni ni-ruler-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            });

            tableBody.innerHTML = html;
        }

        function getNetworkColor(network) {
            const colors = {
                'MTN': 'warning',
                'Airtel': 'danger',
                'Glo': 'success',
                '9mobile': 'info'
            };
            return colors[network] || 'secondary';
        }

        // Inline price editing removed; use modal form only

        async function editPlan(planId) {
            try {
                // Find the plan data from the current table
                const response = await apiFetch('api/pricing.php?action=plans');

                if (response.success) {
                    const plan = response.data.plans.find(p => p.id == planId);
                    if (plan) {
                        openModal('planModal', {
                            ...plan,
                            base_price: plan.base_price,
                            action: 'updatePlan'
                        });
                    }
                } else {
                    showToasted('Failed to load plan details', 'error');
                }
            } catch (error) {
                showToasted('Error loading plan details', 'error');
            }
        }

        async function updateTvMarkup() {
            const markup = document.getElementById('tvMarkup').value;

            if (!markup || markup < 0) {
                showToasted('Please enter a valid markup percentage', 'error');
                return;
            }

            try {
                const response = await apiFetch('api/pricing.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        action: 'updateTvMarkup',
                        percentage: markup
                    })
                });

                if (response.success) {
                    showToasted('TV markup updated successfully', 'success');
                    loadKpis();
                    // Reload pricing to reflect recalculated plan prices
                    loadPricingData();
                } else {
                    showToasted(response.message, 'error');
                }
            } catch (error) {
                showToasted('Error updating TV markup', 'error');
            }
        }

        async function updateDataMarkup() {
            const markup = document.getElementById('dataMarkup').value;

            if (!markup || markup < 0) {
                showToasted('Please enter a valid data markup percentage', 'error');
                return;
            }

            try {
                const response = await apiFetch('api/pricing.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        action: 'updateDataMarkup',
                        percentage: markup
                    })
                });

                if (response.success) {
                    showToasted('Data markup updated successfully', 'success');
                    loadKpis();
                    // Refresh table to reflect recalculated prices
                    loadPricingData();
                } else {
                    showToasted(response.message, 'error');
                }
            } catch (error) {
                showToasted('Error updating data markup', 'error');
            }
        }

        // Override form submission to handle plan updates
        document.getElementById('planForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            data.action = 'updatePlan';

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            apiFetch('api/pricing.php', {
                method: 'POST',
                body: JSON.stringify(data)
            }).then(response => {
                if (response.success) {
                    showToasted('Plan updated successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('planModal')).hide();
                    loadPricingData();
                } else {
                    showToasted(response.message, 'error');
                }
            }).catch(error => {
                showToasted('An error occurred. Please try again.', 'error');
            }).finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    </script>
</body>

</html>