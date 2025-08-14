<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$page_title = 'Notification Templates';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Notification Templates</h1>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#templateModal">
            <i class="bi bi-plus"></i> Add Template
        </button>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<!-- Templates Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Channel</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Welcome Email</td>
                    <td><span class="badge bg-info">Welcome</span></td>
                    <td><span class="badge bg-primary">Email</span></td>
                    <td>Welcome to Dataspeed!</td>
                    <td><span class="badge bg-success">Active</span></td>
                    <td>Jan 15, 2025</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#templateModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-success" title="Test Send">
                                <i class="bi bi-send"></i>
                            </button>
                            <button class="btn btn-outline-danger confirm-delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>Transaction Successful</td>
                    <td><span class="badge bg-success">Transaction</span></td>
                    <td><span class="badge bg-primary">Email</span></td>
                    <td>Your transaction was successful</td>
                    <td><span class="badge bg-success">Active</span></td>
                    <td>Jan 10, 2025</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#templateModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-success" title="Test Send">
                                <i class="bi bi-send"></i>
                            </button>
                            <button class="btn btn-outline-danger confirm-delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>KYC Approved</td>
                    <td><span class="badge bg-warning">KYC</span></td>
                    <td><span class="badge bg-secondary">SMS</span></td>
                    <td>Your KYC has been approved</td>
                    <td><span class="badge bg-success">Active</span></td>
                    <td>Jan 5, 2025</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#templateModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-success" title="Test Send">
                                <i class="bi bi-send"></i>
                            </button>
                            <button class="btn btn-outline-danger confirm-delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Template Modal -->
<div class="modal fade" id="templateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Notification Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Template Name</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="welcome">Welcome</option>
                                    <option value="transaction">Transaction</option>
                                    <option value="kyc">KYC</option>
                                    <option value="notification">General</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Channel</label>
                                <select class="form-select" required>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="push">Push Notification</option>
                                    <option value="in_app">In-App</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Subject/Title</label>
                        <input type="text" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message Body</label>
                        <textarea class="form-control" rows="6" required 
                                  placeholder="Use variables like {{name}}, {{amount}}, {{transaction_id}} etc."></textarea>
                        <small class="form-text text-muted">
                            Available variables: {{name}}, {{email}}, {{amount}}, {{transaction_id}}, {{date}}
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-outline-info">Preview</button>
                <button type="button" class="btn btn-primary">Save Template</button>
            </div>
        </div>
    </div>
</div>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>