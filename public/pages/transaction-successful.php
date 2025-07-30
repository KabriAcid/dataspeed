<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';
require __DIR__ . '/../partials/header.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit;
}

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

$date = date("F j, Y, g:i a", strtotime($txn['created_at']));
$amount = "₦" . number_format($txn['amount'], 2);

$recipient = $txn['description'];
if (preg_match('/for (\d{11})/', $txn['description'], $matches)) {
    $recipient = $matches[1];
}

// Fetch provider icon from service_providers table
$providerIcon = null;
if (!empty($txn['provider_id'])) {
    $stmtIcon = $pdo->prepare("SELECT icon, name FROM service_providers WHERE id = ? LIMIT 1");
    $stmtIcon->execute([$txn['provider_id']]);
    $provider = $stmtIcon->fetch(PDO::FETCH_ASSOC);
    if ($provider) {
        $providerIcon = $provider['icon'];
    }
}

// Use PNG for QR code for html2canvas compatibility
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($txn['reference']) . "&size=120x120&format=png";
?>

<body>
    <main class="container-fluid py-4">
        <div class="receipt-container">
            <!-- Receipt Card -->
            <div id="receipt-section" class="receipt-card">
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
                <div class="text-center mb-5">
                    <h3 class="fw-bold mt-2 mb-1 primary">Payment Successful</h3>
                    <div class="text-secondary small mb-2">Your transaction was completed successfully.</div>
                </div>
                <div class="mb-3">
                    <?php if ($providerIcon): ?>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-semibold">Vendor</span>
                            <img src="../assets/icons/<?= htmlspecialchars($providerIcon) ?>" alt="<?= htmlspecialchars($providerName) ?>" style="height:28px; width:auto;">
                        </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Amount</span>
                        <span class="primary fw-bolder"><?= $amount ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Status</span>
                        <span class="">Successful</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Recipient</span>
                        <span><?= htmlspecialchars($recipient) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Date</span>
                        <span><?= htmlspecialchars($date) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Reference</span>
                        <span><?= htmlspecialchars($txn['reference']) ?></span>
                    </div>
                    <!-- Receipt cut -->
                    <div class="receipt-cut-wrapper">
                        <span class="receipt-cut receipt-cut-left"></span>
                        <span class="receipt-dotted-line"></span>
                        <span class="receipt-cut receipt-cut-right"></span>
                    </div>
                    <!-- PNG QR Code for html2canvas compatibility -->
                    <div class="d-flex justify-content-center mt-3">
                        <img src="<?= $qrCodeUrl ?>" alt="QR Code" style="height:60px;">
                    </div>
                </div>
            </div>
            <!-- Buttons OUTSIDE the receipt-section so they are not included in the download -->
            <div class="d-flex flex-column gap-2 mt-4">
                <button
                    type="button"
                    class="btn primary-btn w-100"
                    id="download-receipt">
                    <i class="ni ni-cloud-download-95 me-2"></i> Download as PNG
                </button>
                <a href="dashboard.php" class="secondary-btn w-100">
                    <i class="ni ni-bold-left me-2 p-0"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/../partials/scripts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('download-receipt').addEventListener('click', function() {
                html2canvas(document.getElementById('receipt-section')).then(function(canvas) {
                    const link = document.createElement('a');
                    link.download = 'receipt-<?= htmlspecialchars($txn['reference']) ?>.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            });
        });
    </script>
    <script>
        setTimeout(() => {
            window.location.href = 'dashboard.php';
        }, 20000);
    </script>
</body>

</html>