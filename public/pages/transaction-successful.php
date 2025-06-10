<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
?>
<script>
    // After 6 seconds, redirect to dashboard
    setTimeout(function() {
        window.location.href = "dashboard.php";
    }, 6000);
    </script>
<body>
    <main class="container-fluid py-4">
        <div class="container">
        <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="p-4">
            <div class="lottie-center">
                <lottie-player
                src="../assets/gif/Lottie-Animation.json"
                background="transparent"
                speed="1"
                style="width: 180px; height: 180px;"
                autoplay
                ></lottie-player>
            </div>
            <h1 class="text-center status-success mb-2">Transaction Successful</h1>
            <p class="text-center mb-4">Your payment was processed successfully.</p>
            <div class="row px-4">
                <div class="col-12 info-row">
                    <div class="label">Transaction ID</div>
                    <div class="value">TXN-99338477</div>
                </div>
                <div class="col-12 info-row">
                    <div class="label">Date</div>
                    <div class="value">June 10, 2025</div>
                </div>
                <div class="col-12 info-row">
                    <div class="label">Amount</div>
                    <div class="value">₦5,000.00</div>
                </div>
                <div class="col-12 info-row">
                    <div class="label">Recipient</div>
                    <div class="value">Airtime (MTN)</div>
                </div>
                <div class="col-12 info-row">
                    <div class="label">Status</div>
                    <div class="value status-success">Successful</div>
                </div>
            </div>
            <div class="d-grid mt-4">
                <a href="dashboard.php" class="btn btn-primary btn-lg">Go to Dashboard</a>
            </div>
            </div>
        </div>
        </div>
    </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>