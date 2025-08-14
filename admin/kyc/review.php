<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Get KYC application
$stmt = $pdo->prepare("
    SELECT k.*, u.name as user_name, u.email as user_email, u.phone as user_phone
    FROM kyc_applications k 
    LEFT JOIN users u ON k.user_id = u.id 
    WHERE k.id = ?
");
$stmt->execute([$id]);
$kyc = $stmt->fetch();

if (!$kyc) {
    header('Location: index.php');
    exit;
}

$success = '';
$error = '';

// Handle form submission
if ($_POST && isset($_POST['review_kyc'])) {
    $decision = $_POST['decision'];
    $reason = trim($_POST['reason'] ?? '');
    
    if (in_array($decision, ['approved', 'rejected'])) {
        try {
            $pdo->beginTransaction();
            
            // Update KYC application
            $stmt = $pdo->prepare("
                UPDATE kyc_applications 
                SET status = ?, admin_reason = ?, reviewed_by = ?, reviewed_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$decision, $reason, $_SESSION['admin_id'], $id]);
            
            // Update user KYC status
            $stmt = $pdo->prepare("UPDATE users SET kyc_status = ? WHERE id = ?");
            $stmt->execute([$decision, $kyc['user_id']]);
            
            $pdo->commit();
            $success = "KYC application {$decision} successfully!";
            
            // Refresh data
            $stmt = $pdo->prepare("
                SELECT k.*, u.name as user_name, u.email as user_email, u.phone as user_phone
                FROM kyc_applications k 
                LEFT JOIN users u ON k.user_id = u.id 
                WHERE k.id = ?
            ");
            $stmt->execute([$id]);
            $kyc = $stmt->fetch();
            
        } catch (Exception $e) {
            $pdo->rollback();
            $error = 'Failed to update KYC application';
        }
    } else {
        $error = 'Invalid decision';
    }
}

$page_title = 'Review KYC';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Review KYC Application</h1>
    <a href="index.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to KYC List
    </a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-8">
        <!-- User Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Name:</strong><br>
                        <?= htmlspecialchars($kyc['user_name']) ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Email:</strong><br>
                        <?= htmlspecialchars($kyc['user_email']) ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Phone:</strong><br>
                        <?= htmlspecialchars($kyc['user_phone']) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Documents -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">KYC Documents</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Document Type:</strong><br>
                        <span class="badge bg-secondary"><?= ucfirst($kyc['document_type']) ?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Document Number:</strong><br>
                        <?= htmlspecialchars($kyc['document_number']) ?>
                    </div>
                </div>
                
                <?php if ($kyc['first_name'] || $kyc['last_name']): ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>First Name:</strong><br>
                        <?= htmlspecialchars($kyc['first_name']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Last Name:</strong><br>
                        <?= htmlspecialchars($kyc['last_name']) ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($kyc['date_of_birth']): ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date of Birth:</strong><br>
                        <?= date('F j, Y', strtotime($kyc['date_of_birth'])) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Address:</strong><br>
                        <?= htmlspecialchars($kyc['address']) ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Document Images -->
                <div class="row">
                    <?php if ($kyc['document_front']): ?>
                    <div class="col-md-6">
                        <strong>Document Front:</strong><br>
                        <img src="/dataspeed/<?= htmlspecialchars($kyc['document_front']) ?>" 
                             class="img-fluid border rounded" 
                             style="max-height: 300px;"
                             alt="Document Front">
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($kyc['document_back']): ?>
                    <div class="col-md-6">
                        <strong>Document Back:</strong><br>
                        <img src="/dataspeed/<?= htmlspecialchars($kyc['document_back']) ?>" 
                             class="img-fluid border rounded" 
                             style="max-height: 300px;"
                             alt="Document Back">
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($kyc['selfie']): ?>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <strong>Selfie:</strong><br>
                        <img src="/dataspeed/<?= htmlspecialchars($kyc['selfie']) ?>" 
                             class="img-fluid border rounded" 
                             style="max-height: 300px;"
                             alt="Selfie">
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Current Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Application Status</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Current Status:</strong><br>
                    <span class="badge status-<?= $kyc['status'] ?> fs-6"><?= ucfirst($kyc['status']) ?></span>
                </div>
                
                <div class="mb-3">
                    <strong>Submitted:</strong><br>
                    <?= date('F j, Y g:i A', strtotime($kyc['created_at'])) ?>
                </div>
                
                <?php if ($kyc['reviewed_at']): ?>
                <div class="mb-3">
                    <strong>Reviewed:</strong><br>
                    <?= date('F j, Y g:i A', strtotime($kyc['reviewed_at'])) ?>
                </div>
                <?php endif; ?>
                
                <?php if ($kyc['admin_reason']): ?>
                <div class="mb-3">
                    <strong>Admin Reason:</strong><br>
                    <div class="alert alert-info mb-0">
                        <?= nl2br(htmlspecialchars($kyc['admin_reason'])) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Review Form -->
        <?php if ($kyc['status'] === 'pending'): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Review Decision</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Decision</label>
                        <select name="decision" class="form-select" required>
                            <option value="">Select Decision</option>
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Reason (Optional)</label>
                        <textarea name="reason" class="form-control" rows="3" 
                                  placeholder="Add a reason for your decision..."></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="review_kyc" class="btn btn-primary">
                            Submit Review
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