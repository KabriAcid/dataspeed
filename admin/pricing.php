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
            
            <!-- Airtime Markup Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Airtime Markup</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="mb-0">Global markup percentage for airtime purchases</p>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="number" class="form-control" id="airtimeMarkup" placeholder="0" min="0" max="100" step="0.1">
                                <span class="input-group-text">%</span>
                                <button class="btn btn-primary" onclick="updateAirtimeMarkup()">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Service Plans -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Service Plans</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Network</th>
                                    <th>Data Size</th>
                                    <th>Validity</th>
                                    <th>Price (₦)</th>
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
                                    <label for="planPrice" class="form-label">Price (₦)</label>
                                    <input type="number" class="form-control" name="price" id="planPrice" required min="0" step="0.01">
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
    
    <?php include 'includes/scripts.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            topbarInit();
            loadPricingData();
            
            // Bind modal form
            const planModal = document.getElementById('planModal');
            bindModalForm(planModal, {
                endpoint: 'api/pricing.php',
                onSuccess: () => {
                    loadPricingData();
                }
            });
        });
        
        async function loadPricingData() {
            const tableBody = document.getElementById('plansTableBody');
            
            try {
                setLoadingState(tableBody, true);
                
                const response = await apiFetch('api/pricing.php?action=plans');
                
                if (response.success) {
                    const { plans, airtime_markup } = response.data;
                    
                    // Update airtime markup field
                    document.getElementById('airtimeMarkup').value = airtime_markup;
                    
                    // Render plans table
                    renderPlansTable(plans);
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
                            <td>
                                <div class="fw-semibold">${plan.name}</div>
                                <small class="text-muted">Code: ${plan.code}</small>
                            </td>
                            <td>
                                <span class="badge bg-${getNetworkColor(plan.network)}">${plan.network}</span>
                            </td>
                            <td>${plan.data_size || '-'}</td>
                            <td>${plan.validity || '-'}</td>
                            <td>
                                <span class="inline-edit fw-semibold" 
                                      onclick="editPrice(this, ${plan.id})"
                                      title="Click to edit">
                                    ₦${parseFloat(plan.price).toLocaleString()}
                                </span>
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
        
        function editPrice(element, planId) {
            const currentPrice = element.textContent.replace('₦', '').replace(/,/g, '');
            
            inlineEdit(element, {
                type: 'number',
                onSave: async (newPrice) => {
                    try {
                        const response = await apiFetch('api/pricing.php', {
                            method: 'POST',
                            body: JSON.stringify({
                                action: 'updatePlanPrice',
                                plan_id: planId,
                                price: newPrice
                            })
                        });
                        
                        if (response.success) {
                            showToasted('Price updated successfully', 'success');
                            return true;
                        } else {
                            showToasted(response.message, 'error');
                            throw new Error(response.message);
                        }
                    } catch (error) {
                        throw error;
                    }
                }
            });
        }
        
        async function editPlan(planId) {
            try {
                // Find the plan data from the current table
                const response = await apiFetch('api/pricing.php?action=plans');
                
                if (response.success) {
                    const plan = response.data.plans.find(p => p.id == planId);
                    if (plan) {
                        openModal('planModal', {
                            ...plan,
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
        
        async function updateAirtimeMarkup() {
            const markup = document.getElementById('airtimeMarkup').value;
            
            if (!markup || markup < 0) {
                showToasted('Please enter a valid markup percentage', 'error');
                return;
            }
            
            try {
                const response = await apiFetch('api/pricing.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        action: 'updateAirtimeMarkup',
                        percentage: markup
                    })
                });
                
                if (response.success) {
                    showToasted('Airtime markup updated successfully', 'success');
                } else {
                    showToasted(response.message, 'error');
                }
            } catch (error) {
                showToasted('Error updating airtime markup', 'error');
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