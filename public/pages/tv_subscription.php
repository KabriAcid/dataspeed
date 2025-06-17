<?php
session_start();

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/header.php';

$tvProviders = getServiceProvider($pdo, 'tv'); // Assumes your function from earlier

?>

<body>
    <main class="container-fluid py-4">

        <!-- Page Header -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">TV Subscription</h5>
                <div class="notification-icon">
                    <a href="notifications.php">
                        <i class="ni ni-bell-55 fs-5 text-gradient text-dark"></i>
                        <span class="notification-badge"></span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Service Selection -->
        <div class="service-section">
            <div class="service-tabs">
                <?php foreach ($tvProviders as $i => $provider): ?>
                    <div
                        class="service-tab<?= $i === 0 ? ' selected-tab' : '' ?>"
                        data-provider="<?= htmlspecialchars($provider['slug']) ?>"
                        data-service-id="<?= (int)$provider['service_id'] ?>"
                        data-type="<?= htmlspecialchars($provider['type']) ?>"
                        style="--brand-color: <?= htmlspecialchars($provider['brand_color']) ?>;">
                        <img src="../assets/icons/<?= htmlspecialchars($provider['icon']) ?>" alt="<?= htmlspecialchars($provider['name']) ?>">
                        <span><?= htmlspecialchars($provider['name']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Subscription Form -->
        <div class="subscription-form mt-4">
            <div class="input-group-container mb-3">
                <input type="text" class="input" id="iuc-number" placeholder="Enter Smart Card/IUC Number">
            </div>

            <div class="input-group-container mb-3">
                <select class="input" id="tv-plan">
                    <option selected disabled>Select Subscription Plan</option>
                </select>
            </div>

            <button type="submit" class="btn w-100 mt-3 primary-btn purchase-btn" disabled>Purchase</button>
        </div>

        <!-- Custom Plan Modal -->
        <div class="modal fade" id="planModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3">
                    <div class="modal-header">
                        <h5 class="modal-title">Choose a Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group" id="planList">
                            <!-- Plans will be injected here -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>



        <?php require __DIR__ . '/../partials/bottom-nav.php'; ?>
        <?php require __DIR__ . '/../partials/pinpad.php'; ?>

    </main>

    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/pin-pad.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const networkTabs = document.querySelectorAll(".service-tab");
            const planSelect = document.getElementById("tv-plan");
            const iucInput = document.getElementById("iuc-number");
            const purchaseBtn = document.querySelector(".purchase-btn");

            const confirmModal = document.getElementById("confirmModal");
            const pinpadModal = document.getElementById("pinpadModal");
            const closeConfirmBtn = document.getElementById("closeConfirm");
            const payBtn = document.getElementById("payBtn");

            const openPlanModalBtn = document.getElementById("openPlanModal");
            const selectedPlanText = document.getElementById("selectedPlanText");
            const planModal = new bootstrap.Modal(document.getElementById("planModal"));
            const planList = document.getElementById("planList");
            const planSelectHidden = document.getElementById("tv-plan");


            // Select Service Tab
            networkTabs.forEach(tab => {
                tab.addEventListener("click", () => {
                    networkTabs.forEach(t => t.classList.remove("selected-tab"));
                    tab.classList.add("selected-tab");

                    const provider = tab.dataset.provider;
                    const brandColor = getComputedStyle(tab).getPropertyValue('--brand-color');

                    planSelect.dataset.brandColor = brandColor; // Just store, don't style select
                    planSelect.value = ""; // Reset any previous selection
                    purchaseBtn.disabled = true; // Reset button
                });
            });


            // Enable purchase button
            [iucInput, planSelect].forEach(el =>
                el.addEventListener("input", validatePurchaseButton)
            );

            // Confirm purchase
            purchaseBtn.addEventListener("click", function(e) {
                e.preventDefault();
                const iuc = iucInput.value.trim();
                const selectedPlan = planSelect.options[planSelect.selectedIndex];

                document.getElementById('confirm-network').innerHTML = selectedPlan.dataset.icon || '';
                document.getElementById('confirm-plan').textContent = selectedPlan.text;
                document.getElementById('confirm-amount').textContent = formattedAmount(selectedPlan.dataset.amount);

                confirmModal.style.display = "flex";
            });

            // Confirm modal events
            closeConfirmBtn?.addEventListener("click", () => confirmModal.style.display = "none");
            confirmModal?.addEventListener("click", (e) => {
                if (e.target === confirmModal) confirmModal.style.display = "none";
            });

            // Pay from modal
            payBtn?.addEventListener("click", () => {
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

            pinpadModal?.addEventListener("click", function(e) {
                if (e.target === pinpadModal) pinpadModal.style.display = "none";
            });

            function validatePurchaseButton() {
                const iuc = iucInput.value.trim();
                const plan = planSelect.value;
                purchaseBtn.disabled = !(iuc && plan);
            }

            function formattedAmount(amount) {
                return amount ? `₦${Number(amount).toLocaleString()}` : "";
            }

            // Trigger first tab click by default (DSTV)
            document.querySelector(".service-tab.selected-tab")?.click();
        });
    </script>
</body>

</html>