<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$page_title = 'Notifications';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Notifications Management</h1>
    <a href="templates.php" class="btn btn-primary">
        <i class="bi bi-plus"></i> Notification Templates
    </a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Send Notification</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Recipients</label>
                        <select class="form-select" required>
                            <option value="">Select Recipients</option>
                            <option value="all">All Users</option>
                            <option value="active">Active Users Only</option>
                            <option value="specific">Specific User</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Channel</label>
                        <select class="form-select" required>
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                            <option value="push">Push Notification</option>
                            <option value="in_app">In-App</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Subject/Title</label>
                        <input type="text" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary">Preview</button>
                        <button type="submit" class="btn btn-primary">Send Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Notifications</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Welcome to Dataspeed</h6>
                                <p class="mb-1 text-muted">Sent to all new users</p>
                                <small class="text-muted">2 hours ago • Email</small>
                            </div>
                            <span class="badge bg-success">Sent</span>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Transaction Failed</h6>
                                <p class="mb-1 text-muted">Sent to user@example.com</p>
                                <small class="text-muted">1 day ago • Email + SMS</small>
                            </div>
                            <span class="badge bg-success">Sent</span>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Maintenance Notice</h6>
                                <p class="mb-1 text-muted">Sent to active users</p>
                                <small class="text-muted">3 days ago • In-App</small>
                            </div>
                            <span class="badge bg-success">Sent</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>