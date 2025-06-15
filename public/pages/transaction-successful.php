<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
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

// Network icon mapping
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
        <div class="form-container">
            <div class="row justify-content-center">
                <div class="">
                    <div class="avatar-sm m-auto d-flex justify-content-center">
                        <img src="<?= htmlspecialchars($networkIcon) ?>" alt="Network" style="height:40px;">
                    </div>
                    <div class="">
                        <h1 class="text-center my-3"><?= $amount ?></h1>
                    </div>
                    <div class="">
                        <div class="lottie-center">
                            <lottie-player
                                src="../assets/gif/Lottie-Animation.json"
                                background="transparent"
                                speed="1"
                                style="width: 180px; height: 180px;"
                                autoplay></lottie-player>
                        </div>
                        <h1 class="text-center status-success mb-2"><i class="ni ni-checkmark text-success"></i> Successful</h1>
                        <div class="row px-4">
                            <div class="col-12 info-row">
                                <div class="label">Transaction ID</div>
                                <div class="value"><?= htmlspecialchars($txn['reference']) ?></div>
                            </div>
                            <div class="col-12 info-row">
                                <div class="label">Recipient</div>
                                <div class="value"><?= htmlspecialchars($recipient) ?></div>
                            </div>
                            <div class="col-12 info-row">
                                <div class="label">Date</div>
                                <div class="value"><?= htmlspecialchars($date) ?></div>
                            </div>
                            <div class="col-12 info-row">
                                <div class="label">Status</div>
                                <div class="value status-success">Successful</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="share-receipt.php?ref=<?= urlencode($txn['reference']) ?>" class="text-center">Share Receipt</a>
                            <a href="dashboard.php" class="btn-link btn shadow">Exit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
</body>

</html>