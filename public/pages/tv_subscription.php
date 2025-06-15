<?php
session_start();

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
$loggedInPhone = $user['phone_number'] ?? '';
?>

<body>
    <main class="container-fluid py-4">

        <!-- Page Header -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">TV Subscription</h5>
                <span></span>
            </div>
        </header>

        <!-- TV Provider Selection -->
        <div class="network-section">
            <div class="network-tabs">
                <div class="network-tab selected-network" data-provider="dstv" style="--brand-color: #00206C;">
                    <img src="../assets/icons/dstv.svg" alt="DSTV"><span>DSTV</span>
                </div>
                <div class="network-tab" data-provider="gotv" style="--brand-color: #A1C823;">
                    <img src="../assets/icons/gotv.svg" alt="GOTV"><span>GOTV</span>
                </div>
                <div class="network-tab" data-provider="startimes" style="--brand-color: #f9a825;">
                    <img src="../assets/icons/startimes.svg" alt="Startimes"><span>Startimes</span>
                </div>
            </div>
        </div>

        <!-- TV Subscription Form -->
        <div class="subscription-form mt-4">
            <!-- IUC Number Input -->
            <div class="input-group-container mb-3">
                <input type="text" class="input" id="iuc-number" placeholder="Enter Smart Card/IUC Number">
            </div>

            <!-- Phone Number Input (optional) -->
            <div class="input-group-container mb-3">
                <span class="input-group-prefix text-xs">
                    <img src="../assets/img/ng.png" alt=""> +234
                </span>
                <input type="tel" class="input phone-input" id="customer-phone" maxlength="10" placeholder="Phone Number (Optional)">
            </div>

            <!-- Plan Selection -->
            <div class="input-group-container mb-3">
                <select class="input" id="tv-plan">
                    <option selected disabled>Select Subscription Plan</option>
                    <!-- Plans to be populated dynamically -->
                </select>
            </div>

            <!-- Purchase Button -->
            <button type="submit" class="btn w-100 mt-3 primary-btn purchase-btn" disabled>Purchase</button>
        </div>


        <?php require __DIR__ . '/../partials/bottom-nav.php'; ?>
        <?php require __DIR__ . '/../partials/pinpad_modal.php'; ?>

    </main>

    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/pin-pad.js"></script>
    <script src="../assets/js/tv-subscription.js"></script>
</body>