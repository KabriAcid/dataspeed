<?php
session_start();
require_once '../../config/config.php';
require_once '../../functions/pricing.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// Handle pricing updates
if ($_POST && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_airtime_markup') {
        $provider_id = $_POST['provider_id'];
        $markup_type = $_POST['markup_type'];
        $markup_value = $_POST['markup_value'];
        
        if (setAirtimeMarkup($provider_id, $markup_type, $markup_value)) {
            $success = 'Airtime markup updated successfully!';
        } else {
            $error = 'Failed to update airtime markup';
        }
    }
}

// Get data for pricing tab
$providers = getProviders();
$services = getServices();
$data_plans = getServicePlans('data');
$tv_plans = getServicePlans('tv');
$electricity_plans = getServicePlans('electricity');

$page_title = 'Settings';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Settings</h1>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<!-- Settings Tabs -->
<ul class="nav nav-tabs" id="settingsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">
            General Settings
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button">
            Pricing Management
        </button>
    </li>
</ul>

<div class="tab-content" id="settingsTabContent">
    <!-- General Settings Tab -->
    <div class="tab-pane fade show active" id="general" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">General Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Site Name</label>
                                <input type="text" class="form-control" name="site_name" value="Dataspeed" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Site Email</label>
                                <input type="email" class="form-control" name="site_email" value="admin@dataspeed.com" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Maintenance Mode</label>
                                <select class="form-select" name="maintenance_mode">
                                    <option value="0">Disabled</option>
                                    <option value="1">Enabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Registration</label>
                                <select class="form-select" name="allow_registration">
                                    <option value="1" selected>Enabled</option>
                                    <option value="0">Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Pricing Management Tab -->
    <div class="tab-pane fade" id="pricing" role="tabpanel">
        <!-- Data Plans -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Data Plans Pricing</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm pricing-table mb-0">
                    <thead>
                        <tr>
                            <th>Provider</th>
                            <th>Plan Name</th>
                            <th>Volume</th>
                            <th>Price (₦)</th>
                            <th>Validity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_plans as $plan): ?>
                        <tr>
                            <td><?= htmlspecialchars($plan['provider_name']) ?></td>
                            <td><?= htmlspecialchars($plan['name']) ?></td>
                            <td><?= $plan['volume'] ?>MB</td>
                            <td class="editable" id="price-<?= $plan['id'] ?>" onclick="toggleEdit('price-<?= $plan['id'] ?>', '<?= $plan['price'] ?>')">
                                <?= number_format($plan['price'], 2) ?>
                            </td>
                            <td><?= $plan['validity'] ?> days</td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle_status">
                                    <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                                    <input type="hidden" name="is_active" value="<?= $plan['is_active'] ? 0 : 1 ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-<?= $plan['is_active'] ? 'success' : 'secondary' ?>">
                                        <?= $plan['is_active'] ? 'Active' : 'Inactive' ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="editPlan(<?= $plan['id'] ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- TV Plans -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">TV Plans Pricing</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm pricing-table mb-0">
                    <thead>
                        <tr>
                            <th>Provider</th>
                            <th>Plan Name</th>
                            <th>Price (₦)</th>
                            <th>Validity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tv_plans as $plan): ?>
                        <tr>
                            <td><?= htmlspecialchars($plan['provider_name']) ?></td>
                            <td><?= htmlspecialchars($plan['name']) ?></td>
                            <td class="editable" id="price-<?= $plan['id'] ?>" onclick="toggleEdit('price-<?= $plan['id'] ?>', '<?= $plan['price'] ?>')">
                                <?= number_format($plan['price'], 2) ?>
                            </td>
                            <td><?= $plan['validity'] ?> days</td>
                            <td>
                                <span class="badge bg-<?= $plan['is_active'] ? 'success' : 'secondary' ?>">
                                    <?= $plan['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Airtime Markup -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Airtime Markup Settings</h5>
            </div>
            <div class="card-body">
                <?php foreach ($providers as $provider): ?>
                    <?php $markup = getAirtimeMarkup($provider['id']); ?>
                    <div class="row align-items-center mb-3">
                        <div class="col-md-3">
                            <strong><?= htmlspecialchars($provider['name']) ?></strong>
                        </div>
                        <div class="col-md-9">
                            <form method="POST" class="row g-2">
                                <input type="hidden" name="action" value="update_airtime_markup">
                                <input type="hidden" name="provider_id" value="<?= $provider['id'] ?>">
                                <div class="col-md-3">
                                    <select name="markup_type" class="form-select form-select-sm">
                                        <option value="percent" <?= $markup['markup_type'] === 'percent' ? 'selected' : '' ?>>Percentage</option>
                                        <option value="fixed" <?= $markup['markup_type'] === 'fixed' ? 'selected' : '' ?>>Fixed Amount</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" name="markup_value" class="form-control form-control-sm" 
                                           value="<?= $markup['markup_value'] ?>" placeholder="0.00">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>