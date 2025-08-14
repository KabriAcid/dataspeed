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

$success = '';
$error = '';

if ($_POST && isset($_POST['update_transaction'])) {
    $status = $_POST['status'];
    $admin_note = trim($_POST['admin_note']);
    
    if (updateTransactionStatus($id, $status, $admin_note)) {
        $success = 'Transaction updated successfully!';
        $transaction = getTransactionById($id); // Refresh data
    } else {
        $error = 'Failed to update transaction';
    }
}

$page_title = 'Edit Transaction';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Transaction</h1>
    <div>
        <a href="view.php?id=<?= $transaction['id'] ?>" class="btn btn-outline-info">
            <i class="bi bi-eye"></i> View
        </a>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Transaction Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ID:</strong> <?= $transaction['id'] ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Reference:</strong> <?= htmlspecialchars($transaction['reference']) ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>User:</strong> <?= htmlspecialchars($transaction['user_name']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Amount:</strong> ₦<?= number_format($transaction['amount'], 2) ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Service:</strong> <?= ucfirst($transaction['service_type']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Provider:</strong> <?= htmlspecialchars($transaction['provider']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Update Transaction</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" <?= $transaction['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="completed" <?= $transaction['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="failed" <?= $transaction['status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Admin Note</label>
                        <textarea name="admin_note" class="form-control" rows="4" placeholder="Add a note about this transaction..."><?= htmlspecialchars($transaction['admin_note']) ?></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" name="update_transaction" class="btn btn-primary">
                            Update Transaction
                        </button>
                        <a href="view.php?id=<?= $transaction['id'] ?>" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>