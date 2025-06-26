<?php
session_start();
require __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['locked_user_id'])) {
    header("Location: login.php");
    exit;
}

$locked_user_id = $_SESSION['locked_user_id'];

// Predefined reasons for account lock
$reasons = [
    "Forgot PIN",
    "Suspicious activity detected",
    "Account locked by mistake",
    "Other"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Locked</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <main class="container py-4">
        <header>
            <h3 class="text-center">Account Locked</h3>
            <p class="text-center">Your account has been locked due to multiple failed attempts. Please submit a complaint to unlock your account.</p>
        </header>

        <form id="accountLockForm" method="POST" action="submit-complaint.php" class="form-container">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($locked_user_id); ?>">

            <div class="form-group">
                <label for="reason">Reason for Account Lock</label>
                <select name="reason" id="reason" class="form-control" required>
                    <option value="">-- Select a Reason --</option>
                    <?php foreach ($reasons as $reason): ?>
                        <option value="<?php echo htmlspecialchars($reason); ?>"><?php echo htmlspecialchars($reason); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="additional_info">Additional Information (Optional)</label>
                <textarea name="additional_info" id="additional_info" class="form-control" rows="4" placeholder="Provide any additional details..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Complaint</button>
        </form>
    </main>
</body>
</html>