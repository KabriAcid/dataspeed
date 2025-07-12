<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/initialize.php';


if (!isset($_SESSION['locked_user_id'])) {
    header("Location: login.php");
    exit;
}

$locked_user_id = $_SESSION['locked_user_id'] ?? null;

// Predefined reasons for account lock
$reasons = [
    "Forgot PIN",
    "Suspicious activity detected",
    "Account locked by mistake",
    "Other"
];
?>

<body>
    <main class="container py-4">
        <header>
            <h3 class="text-center">Account Locked</h3>
            <p class="text-center">Your account has been locked due to multiple failed attempts. Please submit a complaint to unlock your account.</p>
        </header>

        <?php if (isset($_GET['submitted']) && $_GET['submitted'] === 'true'): ?>
            <div class="success-message">
                <h4>Complaint Submitted</h4>
                <p>Your complaint has been submitted successfully. Our team will review it and get back to you shortly.</p>
                <p>Steps to reset your account:</p>
                <ol>
                    <li>Wait for an email or SMS from our support team.</li>
                    <li>Follow the instructions provided to reset your account.</li>
                    <li>If you need further assistance, contact our support team.</li>
                </ol>
            </div>
        <?php else: ?>
            <!-- Show the complaint form -->
            <form id="accountLockForm" method="POST" action="submit_complaint.php" class="form-container">
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

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn primary-btn">Submit Complaint</button>
                </div>
            </form>
        <?php endif; ?>
    </main>
</body>

</html>