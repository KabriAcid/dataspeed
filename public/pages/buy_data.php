<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';
require __DIR__ . '/../partials/header.php';

$loggedInPhone = isset($user['phone_number']) ? $user['phone_number'] : '';
$networkProviders = getServiceProvider($pdo, 'network');
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">Buy Data</h5>
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
                <?php foreach ($networkProviders as $i => $provider): ?>
                    <div
                        class="service-tab<?= $i === 0 ? ' selected-tab' : '' ?>"
                        data-network="<?= htmlspecialchars($provider['slug']) ?>"
                        data-provider-id="<?= (int)$provider['id'] ?>"
                        style="--brand-color: <?= htmlspecialchars($provider['brand_color']) ?>;">
                        <img src="../assets/icons/<?= htmlspecialchars($provider['icon']) ?>" alt="<?= htmlspecialchars($provider['name']) ?>">
                        <span><?= htmlspecialchars($provider['name']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Purchase Tabs -->
        <div class="tabs mb-5">
            <div class="tab-buttons d-flex">
                <button class="tab-btn active" data-tab="self" type="button">Buy For Self</button>
                <button class="tab-btn" data-tab="others" type="button">Buy For Others</button>
            </div>
            <div class="sub-tab-buttons d-flex mt-2">
                <button class="sub-tab-btn active" data-sub="daily" type="button">Daily</button>
                <button class="sub-tab-btn" data-sub="weekly" type="button">Weekly</button>
                <button class="sub-tab-btn" data-sub="monthly" type="button">Monthly</button>
            </div>
            <!-- Plans Section -->
            <div class="tab-content position-relative active">
                <div id="planCards" class="row mt-3">
                    <!-- Plan cards will be dynamically loaded here -->
                </div>

                <!-- Recipient Phone (for Buy For Others) -->
                <div class="input-group-container" id="recipientPhoneWrap" style="display: none;">
                    <span class="input-group-prefix text-xs">
                        <img src="../assets/img/ng.png" alt=""> +234
                    </span>
                    <input type="tel" id="recipientPhone" name="recipient_phone" maxlength="11"
                        placeholder="Phone Number" class="input phone-input" required value="8011111111">
                </div>

                <button type="button" class="btn w-100 mt-3 mb-5 primary-btn" id="purchaseBtn" disabled>Purchase</button>
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
                    <div id="customer-phone" data-raw=""></div>
                    <div class="info-row"><span>Service:</span><span id="confirmService"></span></div>
                    <div class="info-row"><span>Plan:</span><span id="confirmPlan" class="fw-bolder primary fs-6"></span></div>
                    <div class="info-row"><span>Amount:</span><span id="confirmAmount" class="fw-bold"></span></div>
                    <div class="info-row"><span>Validity:</span><span id="confirmValidity" class="fw-bold"></span></div>
                    <div class="info-row">
                        <span>Product</span>
                        <span>
                            <i class="icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 19.51L12.01 19.4989M2 8C8 3.5 16 3.5 22 8M5 12C9 8.99999 15 9 19 12M8.5 15.5C10.7504 14.1 13.2498 14.0996 15.5001 15.5"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </i>&nbsp; Internet Data
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
        <?php require __DIR__ . '/../partials/pinpad.php' ?>
    </main>

    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/pinpad.js"></script>
    <script>
        const networkIcons = {
            MTN: `<img src="../assets/img/icons/mtn.png" alt="MTN" style="height:25px;">`,
            AIRTEL: `<img src="../assets/img/icons/airtel.png" alt="Airtel" style="height:25px;">`,
            GLO: `<img src="../assets/img/icons/glo.png" alt="Glo" style="height:25px;">`,
            '9MOBILE': `<img src="../assets/img/icons/9mobile.png" alt="9Mobile" style="height:25px;">`
        };

        document.addEventListener("DOMContentLoaded", function() {
            // --- Element references ---
            const networkTabs = document.querySelectorAll(".service-tab");
            const tabBtns = document.querySelectorAll(".tab-btn");
            const subTabBtns = document.querySelectorAll(".sub-tab-btn");
            const planCardsContainer = document.getElementById("planCards");
            const recipientPhoneWrap = document.getElementById("recipientPhoneWrap");
            const recipientPhoneInput = document.getElementById("recipientPhone");
            const purchaseBtn = document.getElementById("purchaseBtn");
            const confirmModal = document.getElementById("confirmModal");
            const closeConfirm = document.getElementById("closeConfirm");
            const customerPhone = document.getElementById("customer-phone");
            const confirmService = document.getElementById("confirmService");
            const confirmPlan = document.getElementById("confirmPlan");
            const confirmAmount = document.getElementById("confirmAmount");
            const confirmValidity = document.getElementById("confirmValidity");
            const payBtn = document.getElementById("payBtn");
            const airtelLogo = document.querySelector(".airtel-logo");
            const allPlanCards = document.getElementById("allPlanCards");


            // --- State ---
            let selectedTab = "mtn";
            let selectedProviderId = "1";
            let selectedPlan = null;
            let selectedSub = document.querySelector(".sub-tab-btn.active")?.dataset.sub || "daily";
            let buyFor = "self";

            // --- Service selection ---
            networkTabs.forEach(tab => {
                tab.addEventListener("click", function() {
                    // Update network tab selection
                    networkTabs.forEach(t => t.classList.remove("selected-tab", "active"));
                    tab.classList.add("selected-tab", "active");
                    selectedTab = tab.dataset.network;
                    selectedProviderId = tab.dataset.providerId;

                    if (airtelLogo) {
                        airtelLogo.src = "../assets/icons/airtel-1.png";
                        if (selectedTab === "airtel") airtelLogo.src = "../assets/icons/airtel-2.png";
                    }

                    // Update the background color of the selected plan card
                    const selectedPlanCard = document.querySelector(".plan-card.selected-plan");
                    if (selectedPlanCard) {
                        const brandColor = getComputedStyle(tab).getPropertyValue("--brand-color");
                        selectedPlanCard.style.backgroundColor = brandColor;
                        selectedPlanCard.style.color = "#fff";
                        selectedPlanCard.querySelectorAll('*').forEach(el => el.style.color = "#fff");
                    }

                    // Load plans for the selected network
                    loadPlans();
                });
            });

            // --- Tab selection (Buy for Self/Others) ---
            tabBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    tabBtns.forEach(b => b.classList.remove("active"));
                    btn.classList.add("active");
                    buyFor = btn.dataset.tab;
                    recipientPhoneWrap.style.display = buyFor === "others" ? "flex" : "none";
                    selectedPlan = null;
                    highlightSelectedPlan();
                    purchaseBtn.disabled = true;

                    // Animate tab-content
                    const tabContent = document.querySelector('.tab-content');
                    tabContent.classList.remove('active');
                    setTimeout(() => {
                        tabContent.classList.add('active');
                    }, 10); // Small delay for transition
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
                container.innerHTML = '<div class="d-flex justify-content-center py-4"><span class="loading-spinner"></span></div>';
                sendAjaxRequest(
                    "fetch-data-plans.php",
                    "POST",
                    `provider_id=${encodeURIComponent(selectedProviderId)}&type=${encodeURIComponent(selectedSub)}`,
                    function(response) {
                        if (response.success && Array.isArray(response.plans)) {
                            renderPlans(response.plans, forModal);
                        } else {
                            container.innerHTML = '<div class="text-danger text-center fw-bold text-sm py-4">No plans found.</div>';
                            showToasted(response.message, "error");
                            console.error("Error loading plans:", response.message);
                        }
                    }
                );
            }

            // --- Render plans (2 per row, etc.) ---
            function renderPlans(plans, forModal = false) {
                const container = forModal ? allPlanCards : planCardsContainer;
                container.innerHTML = "";
                plans.forEach((plan, idx) => {
                    const card = document.createElement("div");
                    card.className = "col-4";
                    card.innerHTML = `
                                    <div class="plan-card" data-plan-id="${plan.plan_id}" data-price="${plan.price}" data-volume="${plan.volume}" data-validity="${plan.validity}">
                                        <div class="data-price mb-1" style="font-size:1rem;">₦${Number(plan.price).toLocaleString()}</div>
                                        <div class="data-volume mb-1">${plan.volume}</div>
                                        <div class="data-validity mb-2">${plan.validity}</div>
                                    </div>
                                `;

                    // Plan card click
                    card.querySelector(".plan-card").addEventListener("click", function() {
                        document.querySelectorAll(forModal ? "#allPlanCards .plan-card" : "#planCards .plan-card").forEach(c => {
                            c.classList.remove("selected-plan");
                            c.style.backgroundColor = "";
                            c.style.color = "";
                            c.querySelectorAll('*').forEach(el => el.style.color = "");
                        });
                        this.classList.add("selected-plan");
                        // Set background to network color, text to white
                        const selectedTabTab = document.querySelector('.service-tab.selected-tab');
                        const brandColor = selectedTabTab ? getComputedStyle(selectedTabTab).getPropertyValue('--brand-color') : '#FFCB05';
                        this.style.backgroundColor = brandColor;
                        this.style.color = "#fff";
                        this.querySelectorAll('*').forEach(el => el.style.color = "#fff");

                        selectedPlan = {
                            plan_id: plan.plan_id,
                            price: plan.price,
                            volume: plan.volume,
                            validity: plan.validity
                        };
                        checkPurchaseReady();
                    });
                    container.appendChild(card);
                });
                selectedPlan = null;
                checkPurchaseReady();
            }

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
                const phone = buyFor === "self" ?
                    "<?= $loggedInPhone ?>" :
                    recipientPhoneInput.value.trim();

                // Format phone number
                customerPhone.textContent = formatPhoneNumber(phone);
                customerPhone.setAttribute("data-raw", phone);

                // Insert network SVG
                const networkKey = selectedTab?.toUpperCase() || "MTN";
                confirmService.innerHTML = networkIcons[networkKey] || "";

                confirmPlan.textContent = `${selectedPlan.volume}`;
                confirmAmount.textContent = `₦${Number(selectedPlan.price).toLocaleString()}`;
                confirmValidity.textContent = `${selectedPlan.validity}`;
                confirmModal.style.display = "flex";
            });

            payBtn.addEventListener("click", function() {
                const pinpadModal = document.getElementById("pinpadModal");
                pinpadModal.dataset.amount = selectedPlan.price;

                // Fix: define phone here
                let phone = buyFor === "self" ? "<?= $loggedInPhone ?>" : recipientPhoneInput.value.trim();
                if (phone.length === 10) phone = '0' + phone;
                pinpadModal.dataset.phone = phone;

                pinpadModal.dataset.network = selectedTab;
                pinpadModal.dataset.type = selectedPlan.volume + " (" + selectedPlan.validity + ")";
                pinpadModal.dataset.action = "data";
                pinpadModal.dataset.plan_id = selectedPlan.plan_id;

                let rawAmount = pinpadModal.dataset.amount.replace(/,/g, '');

                sendAjaxRequest("check-balance.php", "POST", `amount=${rawAmount}`, function(response) {
                    if (response.success) {
                        pinpadModal.style.display = "flex";
                    } else {
                        console.log(response.balance);
                        showToasted(response.message, "error");
                    }
                });

                confirmModal.style.display = "none";
            });

            // **Format Phone Number**
            function formatPhoneNumber(num) {
                // Remove all non-digits
                num = num.replace(/\D/g, '');

                // Ensure leading zero
                if (num.length === 10) num = '0' + num;

                // Format as 080 8483 4953
                if (num.length === 11 && num.startsWith('0')) {
                    return `${num.substring(0, 3)} ${num.substring(3, 7)} ${num.substring(7, 11)}`;
                }
                return num;
            }

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
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>