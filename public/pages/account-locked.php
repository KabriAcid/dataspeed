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
    "Multiple failed PIN attempts",
    "Suspicious activity detected",
    "Account locked by mistake",
    "Multiple failed login attempts",
    "Other"
];

// Check if the user has already submitted a complaint
$stmt = $pdo->prepare("SELECT COUNT(*) FROM account_complaints WHERE status = 'pending' AND user_id = ?");
$stmt->execute([$locked_user_id]);
$complaintExists = $stmt->fetchColumn() > 0;
?>

<body>
    <main class="container py-4">
        <?php if ($complaintExists): ?>
            <div class="success-message">
                <h2 class="mb-3">Complaint Submitted</h2>
                <p>Your complaint has been submitted successfully. Our team will review it and get back to you shortly.</p>
                <i>Steps to reset your account:</i>
                <ol>
                    <li class="mt-2">Wait for an email or SMS from our support team.</li>
                    <li class="mt-2">Follow the instructions provided to reset your account.</li>
                    <li class="mt-2">If you need further assistance, contact our support team.</li>
                </ol>
                <p><a href="" class="fw-bold fs-6 primary">Contact us here</a></p>
                <div class="d-flex justify-content-center mt-4">
                    <button id="resend_email" class="outline-btn">Resend Email</button>
                </div>
            </div>
        <?php else: ?>
            <?php if (!isset($_GET['submitted']) || $_GET['submitted'] !== 'true'): ?>
                <header>
                    <h3 class="text-center">Account Locked</h3>
                    <p class="text-center">Your account has been locked due to multiple failed attempts. Please submit a complaint to unlock your account.</p>
                </header>
            <?php endif; ?>

            <!-- Show the complaint form -->
            <form id="accountLockForm" method="POST" class="form-container">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($locked_user_id); ?>">

                <div class="form-group">
                    <label for="reason">Reason for Account Lock</label>
                    <select name="reason" id="reason" class="input">
                        <option value="">-- Select a Reason --</option>
                        <?php foreach ($reasons as $reason): ?>
                            <option value="<?php echo htmlspecialchars($reason); ?>"><?php echo htmlspecialchars($reason); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn primary-btn" id="submit_complain">Submit Complaint</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Overlay -->
        <div id="bodyOverlay" class="body-overlay" style="display: none;">
            <div class="overlay-spinner"></div>
        </div>

        <!-- Copyright text -->
         <p class="text-center text-sm text-secondary mt-5">Copyright @Dataspeed.com.ng. All rights reserved.</p>
    </main>
    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/account-locked.js"></script>
</body>

</html>