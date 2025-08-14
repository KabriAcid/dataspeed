<?php
session_start();
require_once '../../config/config.php';
require_once '../../functions/transactions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$transaction = getTransactionById($id);
if (!$transaction) {
    header('Location: index.php');
    exit;
}

$page_title = 'Transaction Details';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Transaction Details</h1>
    <div>
        <?php if ($transaction['status'] === 'pending'): ?>
            <a href="edit.php?id=<?= $transaction['id'] ?>" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
        <?php endif; ?>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Transaction Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Transaction ID:</strong><br>
                        <code><?= $transaction['id'] ?></code>
                    </div>
                    <div class="col-md-6">
                        <strong>Reference:</strong><br>
                        <code><?= htmlspecialchars($transaction['reference']) ?></code>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>User:</strong><br>
                        <?= htmlspecialchars($transaction['user_name']) ?><br>
                        <small class="text-muted"><?= htmlspecialchars($transaction['user_email']) ?></small>
                    </div>
                    <div class="col-md-6">
                        <strong>Phone:</strong><br>
                        <?= htmlspecialchars($transaction['user_phone']) ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Service Type:</strong><br>
                        <span class="badge bg-info"><?= ucfirst($transaction['service_type']) ?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Provider:</strong><br>
                        <?= htmlspecialchars($transaction['provider']) ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Amount:</strong><br>
                        <span class="h5 text-primary">₦<?= number_format($transaction['amount'], 2) ?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong><br>
                        <span class="badge status-<?= $transaction['status'] ?> fs-6"><?= ucfirst($transaction['status']) ?></span>
                    </div>
                </div>
                
                <?php if ($transaction['recipient']): ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Recipient:</strong><br>
                        <?= htmlspecialchars($transaction['recipient']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Plan/Product:</strong><br>
                        <?= htmlspecialchars($transaction['plan_name'] ?? 'N/A') ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Created:</strong><br>
                        <?= date('F j, Y g:i A', strtotime($transaction['created_at'])) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Updated:</strong><br>
                        <?= date('F j, Y g:i A', strtotime($transaction['updated_at'])) ?>
                    </div>
                </div>
                
                <?php if ($transaction['admin_note']): ?>
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Admin Note:</strong><br>
                        <div class="alert alert-info">
                            <?= nl2br(htmlspecialchars($transaction['admin_note'])) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($transaction['api_response']): ?>
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>API Response:</strong><br>
                        <pre class="bg-light p-3 rounded"><code><?= htmlspecialchars($transaction['api_response']) ?></code></pre>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <?php if ($transaction['status'] === 'pending'): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Admin Actions</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="edit.php?id=<?= $transaction['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Update Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" <?= $transaction['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="completed" <?= $transaction['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="failed" <?= $transaction['status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Admin Note</label>
                        <textarea name="admin_note" class="form-control" rows="3"><?= htmlspecialchars($transaction['admin_note']) ?></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="update_transaction" class="btn btn-primary">
                            Update Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>