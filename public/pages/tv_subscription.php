<?php
session_start();

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
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
                    <img src="../assets/icons/startimes.png" alt="Startimes"><span>Startimes</span>
                </div>
                <div class="network-tab" data-provider="showmax" style="--brand-color: #f9a825;">
                    <img src="../assets/icons/showmax.svg" alt="showmax"><span>Showmax</span>
                </div>
            </div>
        </div>

        <!-- TV Subscription Form -->
        <div class="subscription-form mt-4">
            <!-- IUC Number Input -->
            <div class="input-group-container mb-3">
                <input type="text" class="input" id="iuc-number" placeholder="Enter Smart Card/IUC Number">
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
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Elements
            const planSelect = document.getElementById("tv-plan");
            const iucInput = document.getElementById("iuc-number");
            const purchaseBtn = document.querySelector(".purchase-btn");

            // Modals
            const confirmModal = document.getElementById("confirmModal");
            const pinpadModal = document.getElementById("pinpadModal");
            const closeConfirmBtn = document.getElementById("closeConfirm");
            const payBtn = document.getElementById("payBtn");

            // Event Listeners
<<<<<<< Updated upstream
            [iucInput, phoneInput, planSelect].forEach(el =>
=======
            [iucInput, planSelect].forEach(el =>
>>>>>>> Stashed changes
                el.addEventListener("input", validatePurchaseButton)
            );
            planSelect.addEventListener("change", validatePurchaseButton);

            purchaseBtn.addEventListener("click", function(e) {
                e.preventDefault();
                const iuc = iucInput.value.trim();
<<<<<<< Updated upstream
                const phone = phoneInput.value.trim();
=======
>>>>>>> Stashed changes
                const selectedPlan = planSelect.options[planSelect.selectedIndex];

                document.getElementById('confirm-network').innerHTML = selectedPlan.dataset.icon || '';
                document.getElementById('confirm-plan').textContent = selectedPlan.text;
                document.getElementById('confirm-amount').textContent = formattedAmount(selectedPlan.dataset.amount);

                confirmModal.style.display = "flex";
            });

            closeConfirmBtn.addEventListener("click", () => {
                confirmModal.style.display = "none";
            });

            confirmModal.addEventListener("click", (e) => {
                if (e.target === confirmModal) confirmModal.style.display = "none";
            });

            payBtn.addEventListener("click", () => {
                const amount = document.getElementById('confirm-amount').textContent.replace(/[^\d]/g, '');
                const iuc = iucInput.value.trim();
                const plan = planSelect.value;

                pinpadModal.dataset.amount = amount;
                pinpadModal.dataset.iuc = iuc;
                pinpadModal.dataset.plan = plan;
                pinpadModal.dataset.action = 'tv';

                sendAjaxRequest("check-balance.php", "POST", `amount=${amount}`, function(response) {
                    if (response.success) {
                        pinpadModal.style.display = "flex";
                    } else {
                        showToasted(response.message, "error");
                    }
                });

                confirmModal.style.display = "none";
            });

            pinpadModal.addEventListener("click", function(e) {
                if (e.target === pinpadModal) pinpadModal.style.display = "none";
            });

            function validatePurchaseButton() {
                const iuc = iucInput.value.trim();
                const plan = planSelect.value;
                purchaseBtn.disabled = !(iuc && plan);
            }

<<<<<<< Updated upstream
            function formatPhoneNumber(num) {
                num = num.replace(/\D/g, '');
                if (num.length === 10) num = '0' + num;
                return num.length === 11 ? `${num.substring(0, 3)} ${num.substring(3, 7)} ${num.substring(7, 11)}` : num;
            }

=======
>>>>>>> Stashed changes
            function formattedAmount(amount) {
                return amount ? `₦${Number(amount).toLocaleString()}` : "";
            }
        });
    </script>

</body>