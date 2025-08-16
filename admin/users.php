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
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">Users Management</h1>
                        <p class="page-subtitle">Manage user accounts and permissions</p>
                    </div>
                    <!-- <button class="btn btn-primary" onclick="openCreateUserModal()">
                        <i class="ni ni-fat-add me-2"></i>Add User
                    </button> -->
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search users...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="accStatusFilter">
                                <option value="">All Account Status</option>
                                <option value="active">Active</option>
                                <option value="locked">Locked</option>
                                <option value="banned">Banned</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="regStatusFilter">
                                <option value="">All Registration Status</option>
                                <option value="complete">Complete</option>
                                <option value="incomplete">Incomplete</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary" onclick="loadUsers()">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Users List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th class="d-none d-lg-table-cell">Contact</th>
                                    <th>Balance</th>
                                    <th>Account Status</th>
                                    <th class="d-none d-lg-table-cell">Registration</th>
                                    <th class="d-none d-md-table-cell">Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
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

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="userForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="userId">

                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" id="fullName" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" id="phone" required>
                        </div>

                        <div class="mb-3" id="passwordField">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Account Status</label>
                            <select class="form-select" name="account_status" id="account_status">
                                <option value="active">Active</option>
                                <option value="locked">Locked</option>
                                <option value="banned">Banned</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Registration Status</label>
                            <select class="form-select" name="registration_status" id="registration_status">
                                <option value="complete">Complete</option>
                                <option value="incomplete">Incomplete</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="userDetailsContent">
                    <!-- User details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>

    <script>
        let currentPage = 1;
        let isEditing = false;

        document.addEventListener('DOMContentLoaded', function() {
            topbarInit();
            loadUsers();

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const debouncedSearch = debounce(() => {
                currentPage = 1;
                loadUsers();
            }, 500);

            searchInput.addEventListener('input', debouncedSearch);

            // Status filters
            document.getElementById('accStatusFilter').addEventListener('change', () => {
                currentPage = 1;
                loadUsers();
            });
            document.getElementById('regStatusFilter').addEventListener('change', () => {
                currentPage = 1;
                loadUsers();
            });

            // Pagination
            window.addEventListener('pageChange', (e) => {
                currentPage = e.detail.page;
                loadUsers();
            });

            // Bind modal form
            const userModal = document.getElementById('userModal');
            bindModalForm(userModal, {
                endpoint: 'api/users.php',
                onSuccess: () => {
                    loadUsers();
                }
            });
        });

        async function loadUsers() {
            const tableBody = document.getElementById('usersTableBody');
            const query = document.getElementById('searchInput').value;
            const accStatus = document.getElementById('accStatusFilter').value;
            const regStatus = document.getElementById('regStatusFilter').value;

            try {
                setLoadingState(tableBody, true);

                const params = new URLSearchParams({
                    action: 'list',
                    page: currentPage,
                    query: query,
                    acc_status: accStatus,
                    reg_status: regStatus
                });

                const response = await apiFetch(`api/users.php?${params}`);

                if (response.success) {
                    renderUsersTable(response.data.items);
                    renderPagination(document.getElementById('paginationContainer'), response.data.pagination);
                } else {
                    showToasted('Failed to load users', 'error');
                }

            } catch (error) {
                showToasted('Error loading users', 'error');
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4">Error loading users</td></tr>';
            } finally {
                setLoadingState(tableBody, false);
            }
        }

        function renderUsersTable(users) {
            const tableBody = document.getElementById('usersTableBody');

            if (users.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="ni ni-circle-08"></i>
                                <h3>No users found</h3>
                                <p>Try adjusting your search criteria</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = users.map(user => `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                <i class="ni ni-single-02"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="fw-semibold text-truncate truncate-180" title="${user.full_name}">${user.full_name}</div>
                                <small class="text-muted">ID: ${user.id}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <div class="text-truncate truncate-200" title="${user.email}">${user.email}</div>
                        <small class="text-muted text-truncate truncate-160" title="${user.phone}">${user.phone}</small>
                    </td>
                    <td>
                        <span class="fw-semibold">${formatCurrency(user.balance)}</span>
                    </td>
                    <td>
                        <span class="badge ${getStatusBadgeClass(user.account_status_label)}">${user.account_status_label}</span>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge ${getRegStatusBadgeClass(user.registration_status)}">${user.registration_status}</span>
                    </td>
                    <td class="d-none d-md-table-cell text-nowrap">
                        <div>${formatDate(user.created_at)}</div>
                        ${user.last_login ? `<small class="text-muted">Last: ${formatDate(user.last_login)}</small>` : '<small class="text-muted">Never logged in</small>'}
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="viewUser(${user.id})" title="View">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary" onclick="editUser(${user.id})" title="Edit">
                                <i class="ni ni-ruler-pencil"></i>
                            </button>
                            <button class="btn btn-outline-${user.account_status_label === 'locked' ? 'success' : 'warning'}" 
                                    onclick="toggleUserLock(${user.id}, ${user.account_status_label !== 'locked'})" 
                                    title="${user.account_status_label === 'locked' ? 'Unlock' : 'Lock'}">
                                <i class="ni ni-lock-circle-open"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function getStatusBadgeClass(accountStatusLabel) {
            const classes = {
                'active': 'bg-success',
                'locked': 'bg-danger',
                'banned': 'bg-dark',
                'inactive': 'bg-secondary'
            };
            return classes[accountStatusLabel] || 'bg-secondary';
        }

        function getRegStatusBadgeClass(regStatus) {
            const classes = {
                'complete': 'bg-success',
                'incomplete': 'bg-warning'
            };
            return classes[regStatus] || 'bg-secondary';
        }

        function openCreateUserModal() {
            isEditing = false;
            document.getElementById('userModalTitle').textContent = 'Add User';
            document.getElementById('passwordField').style.display = 'block';
            document.getElementById('password').required = true;
            openModal('userModal');
        }

        async function editUser(userId) {
            try {
                const response = await apiFetch(`api/users.php?action=view&id=${userId}`);

                if (response.success) {
                    isEditing = true;
                    document.getElementById('userModalTitle').textContent = 'Edit User';
                    document.getElementById('passwordField').style.display = 'none';
                    document.getElementById('password').required = false;
                    // Map API fields to form inputs
                    const data = Object.assign({}, response.data, {
                        account_status: response.data.account_status_label,
                        registration_status: response.data.registration_status || 'complete'
                    });
                    openModal('userModal', data);
                } else {
                    showToasted('Failed to load user details', 'error');
                }
            } catch (error) {
                showToasted('Error loading user details', 'error');
            }
        }

        async function viewUser(userId) {
            try {
                const response = await apiFetch(`api/users.php?action=view&id=${userId}`);

                if (response.success) {
                    const user = response.data;
                    document.getElementById('userDetailsContent').innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Personal Information</h6>
                                <table class="table table-borderless">
                                    <tr><td><strong>Full Name:</strong></td><td>${user.full_name}</td></tr>
                                    <tr><td><strong>Email:</strong></td><td>${user.email}</td></tr>
                                    <tr><td><strong>Phone:</strong></td><td>${user.phone}</td></tr>
                                    <tr><td><strong>Account Status:</strong></td><td><span class="badge ${getStatusBadgeClass(user.account_status_label)}">${user.account_status_label}</span></td></tr>
                                    <tr><td><strong>Registration:</strong></td><td><span class="badge ${getRegStatusBadgeClass(user.registration_status)}">${user.registration_status}</span></td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Account Information</h6>
                                <table class="table table-borderless">
                                    <tr><td><strong>Balance:</strong></td><td>${formatCurrency(user.balance)}</td></tr>
                                    <tr><td><strong>KYC Status:</strong></td><td>${user.kyc_status || 'Not verified'}</td></tr>
                                    <tr><td><strong>Joined:</strong></td><td>${formatDate(user.created_at)}</td></tr>
                                    <tr><td><strong>Last Login:</strong></td><td>${user.last_login ? formatDate(user.last_login) : 'Never'}</td></tr>
                                </table>
                            </div>
                        </div>
                    `;

                    openModal('viewUserModal');
                } else {
                    showToasted('Failed to load user details', 'error');
                }
            } catch (error) {
                showToasted('Error loading user details', 'error');
            }
        }

        async function toggleUserLock(userId, lock) {
            const action = lock ? 'lock' : 'unlock';
            const ok = await confirmDialog(`Are you sure you want to ${action} this user?`, {
                title: 'Confirm',
                confirmText: action === 'lock' ? 'Lock User' : 'Unlock User',
                confirmVariant: action === 'lock' ? 'warning' : 'success',
            });
            if (!ok) return;

            try {
                const response = await apiFetch('api/users.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        action: 'toggleLock',
                        id: userId,
                        lock: lock
                    })
                });

                if (response.success) {
                    showToasted(response.message, 'success');
                    await refreshAdminNotifBadge().catch(() => {});
                    loadUsers();
                } else {
                    showToasted(response.message, 'error');
                }
            } catch (error) {
                showToasted('Error updating user status', 'error');
            }
        }

        // Override form submission to handle create vs update
        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            // Set action based on whether we're editing
            data.action = isEditing ? 'update' : 'create';

            // Remove password if editing and it's empty
            if (isEditing && !data.password) {
                delete data.password;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            apiFetch('api/users.php', {
                method: 'POST',
                body: JSON.stringify(data)
            }).then(async response => {
                if (response.success) {
                    showToasted('User saved successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                    await refreshAdminNotifBadge().catch(() => {});
                    loadUsers();
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