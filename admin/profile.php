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
                <h1 class="page-title">Profile Settings</h1>
                <p class="page-subtitle">Manage your account settings and preferences</p>
            </div>

            <div class="row g-4">
                <!-- Profile Overview Card -->
                <div class="col-lg-4">
                    <div class="card profile-card">
                        <div class="card-body text-center">
                            <div class="profile-avatar-container">
                                <img src="../public/favicon.png"
                                    alt="Admin Avatar"
                                    class="profile-avatar"
                                    id="profileAvatar">
                                <div class="avatar-overlay">
                                    <i class="ni ni-camera"></i>
                                    <input type="file" id="avatarUpload" accept="image/*" style="display: none;">
                                </div>
                            </div>

                            <h4 class="profile-name mt-3" id="profileName">-</h4>
                            <p class="profile-role" id="profileRole">-</p>

                            <div class="profile-stats mt-4">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <h5 class="stat-number" id="totalUsers">-</h5>
                                            <p class="stat-label">Total Users</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <h5 class="stat-number" id="totalTransactions">-</h5>
                                            <p class="stat-label">Transactions</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-info mt-4">
                                <div class="info-item">
                                    <i class="ni ni-email-83"></i>
                                    <span id="profileEmail">-</span>
                                </div>
                                <div class="info-item">
                                    <i class="ni ni-mobile-button"></i>
                                    <span id="profilePhone">Not provided</span>
                                </div>
                                <div class="info-item">
                                    <i class="ni ni-calendar-grid-58"></i>
                                    <span id="profileJoined">Joined -</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Settings -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                                        data-bs-target="#personal" type="button" role="tab">
                                        <i class="ni ni-single-02"></i> Personal Info
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="security-tab" data-bs-toggle="tab"
                                        data-bs-target="#security" type="button" role="tab">
                                        <i class="ni ni-lock-circle-open"></i> Security
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="preferences-tab" data-bs-toggle="tab"
                                        data-bs-target="#preferences" type="button" role="tab">
                                        <i class="ni ni-settings-gear-65"></i> Preferences
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="addadmin-tab" data-bs-toggle="tab"
                                        data-bs-target="#addadmin" type="button" role="tab">
                                        <i class="ni ni-circle-08"></i> Add Admin
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content" id="profileTabsContent">
                                <!-- Personal Information Tab -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <form id="personalInfoForm">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">First Name</label>
                                                <input type="text" class="form-control" name="first_name" id="firstName" value="">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" class="form-control" name="last_name" id="lastName" value="">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Username</label>
                                                <input type="text" class="form-control" name="username" id="username" value="">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email Address</label>
                                                <input type="email" class="form-control" name="email" id="email" value="">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control" name="phone" id="phone" value="">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Role</label>
                                                <input type="text" class="form-control" id="role" value="" readonly>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ni ni-check-bold"></i> Update Information
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary ms-2">
                                                <i class="ni ni-curved-next"></i> Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Security Tab -->
                                <div class="tab-pane fade" id="security" role="tabpanel">
                                    <div class="security-section">
                                        <h6 class="section-title">Change Password</h6>
                                        <form id="passwordForm">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label">Current Password</label>
                                                    <input type="password" class="form-control" name="current_password" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">New Password</label>
                                                    <input type="password" class="form-control" name="new_password" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Confirm New Password</label>
                                                    <input type="password" class="form-control" name="confirm_password" required>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="ni ni-lock-circle-open"></i> Update Password
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <hr class="my-4">

                                    <div class="security-section">
                                        <h6 class="section-title">Two-Factor Authentication</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1">Secure your account with 2FA</p>
                                                <small class="text-muted">Add an extra layer of security to your account</small>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="twoFactorSwitch">
                                                <label class="form-check-label" for="twoFactorSwitch">Enable 2FA</label>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="security-section">
                                        <h6 class="section-title">Login Sessions</h6>
                                        <div class="session-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="session-info">
                                                        <i class="ni ni-laptop text-success"></i>
                                                        <span class="session-device">Current Session</span>
                                                        <span class="badge badge-success ms-2">Active</span>
                                                    </div>
                                                    <small class="text-muted">
                                                        Last login: <span id="lastLogin">-</span>
                                                    </small>
                                                </div>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="ni ni-fat-remove"></i> Revoke
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preferences Tab -->
                                <div class="tab-pane fade" id="preferences" role="tabpanel">
                                    <form id="preferencesForm">
                                        <div class="preferences-section">
                                            <h6 class="section-title">Notifications</h6>
                                            <div class="preference-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <label class="form-label mb-1">Email Notifications</label>
                                                        <small class="text-muted d-block">Receive email alerts for important events</small>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="emailNotifications">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="preference-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <label class="form-label mb-1">Transaction Alerts</label>
                                                        <small class="text-muted d-block">Get notified of new transactions</small>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="transactionAlerts">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <div class="preferences-section">
                                            <h6 class="section-title">Display Settings</h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Theme</label>
                                                    <select class="form-select" name="theme" id="theme">
                                                        <option value="light">Light</option>
                                                        <option value="dark">Dark</option>
                                                        <option value="auto">Auto</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Language</label>
                                                    <select class="form-select" name="language" id="language">
                                                        <option value="en">English</option>
                                                        <option value="fr">French</option>
                                                        <option value="es">Spanish</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ni ni-check-bold"></i> Save Preferences
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Add Admin Tab (super admins only) -->
                                <div class="tab-pane fade" id="addadmin" role="tabpanel">
                                    <div id="addAdminGate" class="alert alert-warning d-none">Only super admins can add new admins.</div>
                                    <form id="addAdminForm" class="mt-2">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Full Name</label>
                                                <input type="text" class="form-control" name="name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Role</label>
                                                <select class="form-select" name="role">
                                                    <option value="manager">Manager</option>
                                                    <option value="support">Support</option>
                                                    <option value="super">Super</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Status</label>
                                                <select class="form-select" name="status">
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <div class="alert alert-info mb-0">
                                                    Default password will be: <strong>Pa$$w0rd!</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ni ni-check-bold"></i> Create Admin
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/scripts.php'; ?>
    <script src="assets/js/profile.js"></script>
</body>

</html>