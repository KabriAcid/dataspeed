<?php
session_start();
require_once '../config/config.php';

// Check admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get dashboard stats
require_once '../functions/users.php';
require_once '../functions/transactions.php';

$user_stats = getUserStats();
$transaction_stats = getTransactionStats();

$page_title = 'Dashboard';
include '../includes/admin/header.php';
include '../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Dashboard</h1>
    <div class="text-muted">
        Welcome back, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>
    </div>
</div>

<!-- Dashboard Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card dashboard-card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-people text-primary display-4"></i>
                <div class="display-6 text-primary"><?= $user_stats['active_users'] ?></div>
                <div class="text-muted">Active Users</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card dashboard-card border-warning">
            <div class="card-body text-center">
                <i class="bi bi-shield-exclamation text-warning display-4"></i>
                <div class="display-6 text-warning"><?= $user_stats['kyc_pending'] ?></div>
                <div class="text-muted">KYC Pending</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card dashboard-card border-success">
            <div class="card-body text-center">
                <i class="bi bi-credit-card text-success display-4"></i>
                <div class="display-6 text-success"><?= $transaction_stats['today_transactions'] ?></div>
                <div class="text-muted">Today's Transactions</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card dashboard-card border-danger">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                <div class="display-6 text-danger"><?= $transaction_stats['failed_transactions'] ?></div>
                <div class="text-muted">Failed Transactions</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="users/create.php" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus"></i> Add New User
                    </a>
                    <a href="notifications/templates.php" class="btn btn-outline-secondary">
                        <i class="bi bi-envelope"></i> Send Notification
                    </a>
                    <a href="settings/" class="btn btn-outline-info">
                        <i class="bi bi-gear"></i> System Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">System Status</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col">
                        <div class="h4 text-success mb-1">₦<?= number_format($transaction_stats['today_amount'], 2) ?></div>
                        <small class="text-muted">Today's Revenue</small>
                    </div>
                    <div class="col">
                        <div class="h4 text-info mb-1"><?= $transaction_stats['pending_transactions'] ?></div>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/admin/footer.php';
include '../includes/admin/scripts.php';
?>