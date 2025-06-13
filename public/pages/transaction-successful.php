<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
?>
<script>
    // After 6 seconds, redirect to dashboard
    // setTimeout(function() {
    //     window.location.href = "dashboard.php";
    // }, 6000);
</script>

<body>
    <main class="container-fluid py-4">
        <div class="form-container">
            <div class="row justify-content-center">
                <div class="">
                    <div class="avatar-sm m-auto d-flex justify-content-center">
                        <img src="../assets/img/icons/mtn.png" alt="" srcset="">
                    </div>
                    <div class="">
                        <h1 class="text-center my-3">₦5,000.00</h1>
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
                                <div class="value">TXN-99338477</div>
                            </div>
                            <div class="col-12 info-row">
                                <div class="label">Recipient</div>
                                <div class="value">090685456788</div>
                            </div>
                            <div class="col-12 info-row">
                                <div class="label">Date</div>
                                <div class="value">June 10, 2025</div>
                            </div>
                            <div class="col-12 info-row">
                                <div class="label">Status</div>
                                <div class="value status-success">Successful</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="" class="secondary-btn d-block text-center shadow">Share Receipt</a>
                            <a href="" class="btn-link btn shadow">Exit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>