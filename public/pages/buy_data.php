<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';

$loggedInPhone = isset($user['phone_number']) ? $user['phone_number'] : '';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Data Bundles</h5>
        </header>

        <!-- Network Selection -->
        <div class="network-section mb-3">
            <div class="network-tabs d-flex justify-content-between">
                <div class="network-tab active" data-network="mtn" data-provider-id="1" style="--brand-color: #ffcc00;">
                    <img src="../assets/icons/mtn_logo.svg" alt="MTN">
                    <span>MTN</span>
                </div>
                <div class="network-tab" data-network="airtel" data-provider-id="2" style="--brand-color: #EB1922;">
                    <img src="../assets/icons/airtel-logo-1.svg" alt="Airtel" class="airtel-logo">
                    <span>Airtel</span>
                </div>
                <div class="network-tab" data-network="glo" data-provider-id="3" style="--brand-color: #4BB44E;">
                    <img src="../assets/icons/glo_logo.svg" alt="Glo">
                    <span>Glo</span>
                </div>
                <div class="network-tab" data-network="9mobile" data-provider-id="4" style="--brand-color: #D6E806;">
                    <img src="../assets/icons/9mobile_logo.svg" alt="9Mobile">
                    <span>9Mobile</span>
                </div>
            </div>
        </div>

        <!-- Purchase Tabs -->
        <div class="tabs mb-3">
            <div class="tab-buttons d-flex">
                <button class="tab-btn active" data-tab="self" type="button">Buy For Self</button>
                <button class="tab-btn" data-tab="others" type="button">Buy For Others</button>
            </div>
            <div class="sub-tab-buttons d-flex mt-2">
                <button class="sub-tab-btn active" data-sub="daily" type="button">Daily</button>
                <button class="sub-tab-btn" data-sub="weekly" type="button">Weekly</button>
                <button class="sub-tab-btn" data-sub="monthly" type="button">Monthly</button>
            </div>
        </div>

        <!-- Recipient Phone (for Buy For Others) -->
        <div class="mb-3" id="recipientPhoneWrap" style="display: none;">
            <label for="recipientPhone" class="form-label">Recipient Phone</label>
            <input type="tel" class="form-control" id="recipientPhone" placeholder="Enter recipient phone number">
        </div>

        <!-- Plans Section -->
        <div class="tab-content position-relative active">
            <div id="planCards" class="row g-3">
                <!-- Plan cards will be dynamically loaded here -->
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button type="button" class="btn btn-link" id="seeAllPlansBtn">
                    <span class="me-1">See All</span>
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="#141C25" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <button type="button" class="btn w-100 mt-3 primary-btn" id="purchaseBtn" disabled>Purchase</button>
        </div>

        <!-- Plans Modal -->
        <div id="plansModal" class="modal-overlay" style="display: none;">
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h5 class="modal-title">All Plans</h5>
                    <button class="close-btn" id="closePlansModal" type="button">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="allPlanCards" class="row g-3"></div>
                </div>
            </div>
        </div>
        <!-- Confirm Modal -->
        <div id="confirmModal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Purchase</h5>
                    <button class="close-btn" id="closeConfirm" type="button">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-sm text-secondary mb-1 text-center">Send to</p>
                    <div id="customerPhone" data-raw=""></div>
                    <div class="info-row"><span>Network:</span><span id="confirmNetwork"></span></div>
                    <div class="info-row"><span>Plan:</span><span id="confirmPlan" class="fw-bold"></span></div>
                    <div class="info-row"><span>Amount:</span><span id="confirmAmount" class="fw-bolder primary fs-6"></span></div>
                    <div class="info-row">
                        <span>Product</span>
                        <span>
                            <i class="icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 19.51L12.01 19.4989M2 8C8 3.5 16 3.5 22 8M5 12C9 8.99999 15 9 19 12M8.5 15.5C10.7504 14.1 13.2498 14.0996 15.5001 15.5"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </i> Internet Data
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="pay-btn" id="payBtn" type="button">Pay</button>
                </div>
            </div>
        </div>

        <!-- Bottom navigation -->
        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
        <?php require __DIR__ . '/../partials/pinpad_modal.php' ?>
    </main>

    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/pin-pad.js"></script>
    <script>
        const networkSVGs = {
            MTN: `<img src="../assets/img/icons/mtn.png" alt="MTN" style="height:32px;">`,
            AIRTEL: `<img src="../assets/img/icons/airtel.png" alt="Airtel" style="height:32px;">`,
            GLO: `<img src="../assets/img/icons/glo.png" alt="Glo" style="height:32px;">`,
            '9MOBILE': `<img src="../assets/img/icons/9mobile.png" alt="9Mobile" style="height:32px;">`
        };

        document.addEventListener("DOMContentLoaded", function() {
            // --- Element references ---
            const networkTabs = document.querySelectorAll(".network-tab");
            const tabBtns = document.querySelectorAll(".tab-btn");
            const subTabBtns = document.querySelectorAll(".sub-tab-btn");
            const planCardsContainer = document.getElementById("planCards");
            const recipientPhoneWrap = document.getElementById("recipientPhoneWrap");
            const recipientPhoneInput = document.getElementById("recipientPhone");
            const purchaseBtn = document.getElementById("purchaseBtn");
            const confirmModal = document.getElementById("confirmModal");
            const closeConfirm = document.getElementById("closeConfirm");
            const customerPhone = document.getElementById("customerPhone");
            const confirmNetwork = document.getElementById("confirmNetwork");
            const confirmPlan = document.getElementById("confirmPlan");
            const confirmAmount = document.getElementById("confirmAmount");
            const payBtn = document.getElementById("payBtn");
            const airtelLogo = document.querySelector(".airtel-logo");
            const seeAllPlansBtn = document.getElementById("seeAllPlansBtn");
            const plansModal = document.getElementById("plansModal");
            const closePlansModal = document.getElementById("closePlansModal");
            const allPlanCards = document.getElementById("allPlanCards");


            // --- State ---
            let selectedNetwork = document.querySelector(".network-tab.active")?.dataset.network || null;
            let selectedProviderId = document.querySelector(".network-tab.active")?.dataset.providerId || null;
            let selectedPlan = null;
            let selectedSub = document.querySelector(".sub-tab-btn.active")?.dataset.sub || "daily";
            let buyFor = "self";

            // --- Network selection ---
            networkTabs.forEach(tab => {
                tab.addEventListener("click", function() {
                    networkTabs.forEach(t => t.classList.remove("active"));
                    tab.classList.add("active");
                    selectedNetwork = tab.dataset.network;
                    selectedProviderId = tab.dataset.providerId;

                    airtelLogo.src = "../assets/icons/airtel-logo-1.svg";
                    if (selectedNetwork === "airtel") airtelLogo.src = "../assets/icons/airtel-logo-2.svg";
                    loadPlans();
                });
            });

            // --- Tab selection (Buy for Self/Others) ---
            tabBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    tabBtns.forEach(b => b.classList.remove("active"));
                    btn.classList.add("active");
                    buyFor = btn.dataset.tab;
                    // Show/hide recipient phone input
                    recipientPhoneWrap.style.display = buyFor === "others" ? "block" : "none";
                    selectedPlan = null;
                    highlightSelectedPlan();
                    purchaseBtn.disabled = true;
                });
            });

            // --- Sub-tab selection (Daily/Weekly/Monthly) ---
            subTabBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    subTabBtns.forEach(b => b.classList.remove("active"));
                    btn.classList.add("active");
                    selectedSub = btn.dataset.sub;
                    loadPlans();
                });
            });

            // --- Load plans via AJAX (for both main and modal) ---
            function loadPlans(forModal = false) {
                if (!selectedProviderId || !selectedSub) return;
                const container = forModal ? allPlanCards : planCardsContainer;
                container.innerHTML = '<div class="text-center py-4">Loading plans...</div>';
                sendAjaxRequest(
                    "fetch-plans.php",
                    "POST",
                    `provider_id=${encodeURIComponent(selectedProviderId)}&type=${encodeURIComponent(selectedSub)}`,
                    function(response) {
                        if (response.success && Array.isArray(response.plans)) {
                            renderPlans(response.plans, forModal);
                            showToasted(response.message, "success");
                            console.log(response.message);
                        } else {
                            container.innerHTML = '<div class="text-danger text-center py-4">No plans found.</div>';
                            showToasted(response.message, "error");
                            console.error("Error loading plans:", response.message);
                        }
                    }
                );
            }

            // --- Render plans (2 per row, Awoof badge, etc.) ---
            function renderPlans(plans, forModal = false) {
                const container = forModal ? allPlanCards : planCardsContainer;
                container.innerHTML = "";
                plans.forEach((plan, idx) => {
                    const card = document.createElement("div");
                    card.className = "col-12 col-md-6";
                    card.innerHTML = `
                <div class="plan-card border p-3 h-100" data-plan-id="${plan.plan_id}" data-price="${plan.price}" data-volume="${plan.volume}" data-validity="${plan.validity}">
                    <div class="data-price text-secondary mb-1" style="font-size:1rem;">₦${Number(plan.price).toLocaleString()}</div>
                    <div class="data-volume mb-1">${plan.volume}</div>
                    <div class="data-validity text-secondary mb-2">${plan.validity}</div>
                    <span class="awoof-badge">AWOOF</span>
                </div>
            `;
                    // Plan card click
                    card.querySelector(".plan-card").addEventListener("click", function() {
                        document.querySelectorAll(forModal ? "#allPlanCards .plan-card" : "#planCards .plan-card").forEach(c => c.classList.remove("selected-plan"));
                        this.classList.add("selected-plan");
                        selectedPlan = {
                            plan_id: plan.plan_id,
                            price: plan.price,
                            volume: plan.volume,
                            validity: plan.validity
                        };
                        checkPurchaseReady();
                        if (forModal) {
                            plansModal.style.display = "none";
                        }
                    });
                    container.appendChild(card);
                });
                selectedPlan = null;
                checkPurchaseReady();
            }

            // --- See All Plans Modal ---
            seeAllPlansBtn.addEventListener("click", function() {
                plansModal.style.display = "flex";
                loadPlans(true);
            });
            closePlansModal.addEventListener("click", function() {
                plansModal.style.display = "none";
            });
            plansModal.addEventListener("click", function(e) {
                if (e.target === plansModal) plansModal.style.display = "none";
            });

            // ...rest of your logic (network/tab selection, purchase, etc.)...
            // --- Highlight selected plan (reset) ---
            function highlightSelectedPlan() {
                document.querySelectorAll(".plan-card").forEach(c => c.classList.remove("selected-plan"));
            }

            // --- Enable/disable Purchase button ---
            function checkPurchaseReady() {
                if (selectedPlan && (buyFor === "self" || (buyFor === "others" && recipientPhoneInput.value.trim().length >= 10))) {
                    purchaseBtn.disabled = false;
                } else {
                    purchaseBtn.disabled = true;
                }
            }

            // --- Recipient phone input validation ---
            recipientPhoneInput.addEventListener("input", checkPurchaseReady);

            // --- Purchase button: show confirm modal ---
            purchaseBtn.addEventListener("click", function() {
                // Fill confirm modal
                customerPhone.textContent = buyFor === "self" ? "My Number" : recipientPhoneInput.value.trim();
                customerPhone.setAttribute("data-raw", buyFor === "self" ? "<?= $loggedInPhone ?>" : recipientPhoneInput.value.trim());
                confirmNetwork.textContent = selectedNetwork?.toUpperCase() || "";
                confirmPlan.textContent = `${selectedPlan.volume} (${selectedPlan.validity})`;
                confirmAmount.textContent = `₦${Number(selectedPlan.price).toLocaleString()}`;
                confirmModal.style.display = "flex";
            });

            // --- Close confirm modal ---
            closeConfirm.addEventListener("click", function() {
                confirmModal.style.display = "none";
            });

            // --- Hide confirm modal on overlay click ---
            confirmModal.addEventListener("click", function(e) {
                if (e.target === confirmModal) confirmModal.style.display = "none";
            });

            // --- Initial load ---
            loadPlans();
        });
    </script>

    <!-- FontAwesome CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>