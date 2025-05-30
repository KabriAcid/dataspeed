<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Buy Data</h5>
        </header>

        <div class="network-section">
            <div class="network-tabs">
                <div class="network-tab" id="mtn-tab" data-network="mtn" style="--brand-color: #ffcc00;">
                    <img src="../../assets/img/icons/mtn_logo.svg" alt="mtn-logo">
                    <span>MTN</span>
                </div>
                <div class="network-tab" id="airtel-tab" data-network="airtel" style="--brand-color: #ed1c24;">
                    <img src="../../assets/img/icons/airtel_logo.svg" alt="airtel-logo">
                    <span>Airtel</span>
                </div>
                <div class="network-tab" id="glo-tab" data-network="glo" style="--brand-color: #008000;">
                    <img src="../../assets/img/icons/glo_logo.svg" alt="glo-logo">
                    <span>Glo</span>
                </div>
                <div class="network-tab" id="9mobile-tab" data-network="9mobile" style="--brand-color: #006e62;">
                    <img src="../../assets/img/icons/9mobile_logo.svg" alt="9mobile-logo">
                    <span>9Mobile</span>
                </div>
            </div>
        </div>

        <div class="plans-section">
            <div class="d-flex justify-content-between">
                <!-- Card  -->
                <div>
                    <div class="card p-0 plan-card border-0 mb-3 shadow">
                        <div class="card-body px-3 py-2 text-dark rounded-3">
                            <div class="text-center">
                                <p id="data-amount">&#8358;800</p>
                                <h5 id="data-volume">1.2GB</h5>
                                <p id="data-duration">7 DAYS</p>
                            </div>
                            <!-- <span class="badge bg-dark-subtle text-dark fw-semibold">AWOOF</span> -->
                        </div>
                    </div>
                </div>
                <!-- Card  -->
                <div>
                    <div class="card p-0 plan-card border-0 mb-3 shadow">
                        <div class="card-body px-3 py-2 text-dark rounded-3">
                            <div class="text-center">
                                <p id="data-amount">&#8358;800</p>
                                <h5 id="data-volume">1.2GB</h5>
                                <p id="data-duration">7 DAYS</p>
                            </div>
                            <!-- <span class="badge bg-dark-subtle text-dark fw-semibold">AWOOF</span> -->
                        </div>
                    </div>
                </div>
                <!-- Card  -->
                <div>
                    <div class="card p-0 plan-card border-0 mb-3 shadow">
                        <div class="card-body px-3 py-2 text-dark rounded-3">
                            <div class="text-center">
                                <p id="data-amount">&#8358;800</p>
                                <h5 id="data-volume">1.2GB</h5>
                                <p id="data-duration">7 DAYS</p>
                            </div>
                            <!-- <span class="badge bg-dark-subtle text-dark fw-semibold">AWOOF</span> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <form action="" method="post">
            <input type="number" name="phone_number" class="input" placeholder="Phone Number" maxlength="10">
            <button type="submit" class="btn w-100 mt-3 primary-btn">Purchase</button>
        </form>

        <!-- <div class="see-all" onclick="openModal()">SEE ALL</div> -->


        <!-- Bottom navigation -->
        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>
    <!-- FontAwesome CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>