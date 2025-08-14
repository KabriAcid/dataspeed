<?php
session_start();
require_once '../../config/config.php';
require_once '../../functions/users.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$user = getUserById($id);
if (!$user) {
    header('Location: index.php');
    exit;
}

// Get recent transactions
$stmt = $pdo->prepare("
    SELECT * FROM transactions 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 10
");
$stmt->execute([$id]);
$recent_transactions = $stmt->fetchAll();

$page_title = 'View User';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">User Details</h1>
    <div>
        <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <!-- User Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Name:</strong><br>
                        <?= htmlspecialchars($user['name']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong><br>
                        <?= htmlspecialchars($user['email']) ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Phone:</strong><br>
                        <?= htmlspecialchars($user['phone']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Balance:</strong><br>
                        ₦<?= number_format($user['balance'], 2) ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Role:</strong><br>
                        <span class="badge bg-info"><?= ucfirst($user['role']) ?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong><br>
                        <span class="badge status-<?= $user['status'] ?>"><?= ucfirst($user['status']) ?></span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>KYC Status:</strong><br>
                        <span class="badge status-<?= $user['kyc_status'] ?>"><?= ucfirst($user['kyc_status']) ?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Created:</strong><br>
                        <?= date('F j, Y g:i A', strtotime($user['created_at'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Transactions</h5>
                <a href="../transactions/?user_id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_transactions): ?>
                            <?php foreach ($recent_transactions as $txn): ?>
                            <tr>
                                <td><?= $txn['id'] ?></td>
                                <td><?= ucfirst($txn['service_type']) ?></td>
                                <td>₦<?= number_format($txn['amount'], 2) ?></td>
                                <td><span class="badge status-<?= $txn['status'] ?>"><?= ucfirst($txn['status']) ?></span></td>
                                <td><?= date('M j, Y', strtotime($txn['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No transactions found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit User
                    </a>
                    <?php if ($user['status'] === 'active'): ?>
                        <button class="btn btn-outline-warning" onclick="if(confirm('Suspend this user?')) location.href='?id=<?= $user['id'] ?>&action=suspend'">
                            <i class="bi bi-pause"></i> Suspend User
                        </button>
                    <?php else: ?>
                        <button class="btn btn-outline-success" onclick="if(confirm('Activate this user?')) location.href='?id=<?= $user['id'] ?>&action=activate'">
                            <i class="bi bi-play"></i> Activate User
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-outline-danger" onclick="if(confirm('Reset user PIN?')) location.href='?id=<?= $user['id'] ?>&action=reset_pin'">
                        <i class="bi bi-key"></i> Reset PIN
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>