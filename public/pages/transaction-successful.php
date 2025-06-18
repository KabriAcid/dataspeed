<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/header.php';

// Get latest successful transaction for this user
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit;
}

// Optionally, get a specific transaction by reference (if passed via GET)
$reference = $_GET['ref'] ?? null;

if ($reference) {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND reference = ? AND status = 'success' ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$user_id, $reference]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND status = 'success' ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$user_id]);
}
$txn = $stmt->fetch();

if (!$txn) {
    echo "<h2 class='text-center mt-5'>No successful transaction found.</h2>";
    exit;
}

// Service icon mapping
$networkIcons = [
    1 => "../assets/img/icons/mtn.png",
    2 => "../assets/img/icons/airtel.png",
    3 => "../assets/img/icons/glo.png",
    4 => "../assets/img/icons/9mobile.png"
];
$networkIcon = $networkIcons[$txn['provider_id']] ?? "../assets/img/icons/mtn.png";

// Format date
$date = date("F j, Y, g:i a", strtotime($txn['created_at']));

// Format amount
$amount = "₦" . number_format($txn['amount'], 2);

// Recipient (phone or email, depending on service)
$recipient = $txn['description'];
if (preg_match('/for (\d{11})/', $txn['description'], $matches)) {
    $recipient = $matches[1];
}
?>

<body>
    <main class="container-fluid py-4">
        <div class="receipt-container">
            <!-- Lottie Animation -->
            <div class="d-flex justify-content-center">
                <lottie-player
                    src="../assets/gif/Lottie-Animation.json"
                    background="transparent"
                    speed="1"
                    style="width: 180px; height: 180px;"
                    autoplay>
                </lottie-player>
            </div>

            <!-- Receipt Card -->
            <div class="receipt-card">
                <div class="text-center mb-3">
                    <i class="ni ni-checkmark text-success" style="font-size:2rem;"></i>
                    <h4 class="fw-bold mt-2 mb-1">Payment Successful</h4>
                    <div class="text-secondary small mb-2">Your transaction was completed successfully.</div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Amount</span>
                        <span><?= $amount ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Status</span>
                        <span class="text-success">Successful</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Recipient</span>
                        <span><?= htmlspecialchars($recipient) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Date</span>
                        <span><?= htmlspecialchars($date) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Transaction ID</span>
                        <span><?= htmlspecialchars($txn['reference']) ?></span>
                    </div>
                    <!-- Fake Barcode -->
                    <div class="d-flex justify-content-center mt-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= urlencode($txn['reference']) ?>&size=120x40&format=svg" alt="barcode" style="height:40px;">
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 mt-4">
                    <a href="share-receipt.php?ref=<?= urlencode($txn['reference']) ?>" class="btn btn-dark w-100">Download Receipt</a>
                    <a href="dashboard.php" class="btn btn-outline-secondary w-100">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </main>
    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>