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

$errors = [];
$success = '';

if ($_POST) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = $_POST['role'] ?? 'user';
    $status = $_POST['status'] ?? 'active';
    
    // Validation
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
    if (empty($phone)) $errors[] = 'Phone number is required';
    
    // Check if email exists (exclude current user)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $id]);
    if ($stmt->fetch()) {
        $errors[] = 'Email already exists';
    }
    
    if (empty($errors)) {
        if (updateUser($id, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'status' => $status
        ])) {
            $success = 'User updated successfully!';
            $user = getUserById($id); // Refresh user data
        } else {
            $errors[] = 'Failed to update user';
        }
    }
}

$page_title = 'Edit User';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit User</h1>
    <div>
        <a href="view.php?id=<?= $user['id'] ?>" class="btn btn-outline-info">
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

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $user['name']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Phone *</label>
                        <input type="tel" class="form-control" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? $user['phone']) ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="user" <?= ($_POST['role'] ?? $user['role']) === 'user' ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= ($_POST['role'] ?? $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= ($_POST['status'] ?? $user['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($_POST['status'] ?? $user['status']) === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="suspended" <?= ($_POST['status'] ?? $user['status']) === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="index.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>