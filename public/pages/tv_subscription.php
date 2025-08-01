<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';
require __DIR__ . '/../partials/header.php';

$loggedInPhone = isset($user['phone_number']) ? $user['phone_number'] : '';
$providers = getServiceProvider($pdo, 'TV');
?>

<body>
    <main class="container-fluid py-5">
        <!-- Header Section -->
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
                <?php foreach ($providers as $i => $provider): ?>
                    <div class="service-tab<?= $i === 0 ? ' selected-tab' : '' ?>"
                        data-network="<?= htmlspecialchars($provider['slug']) ?>"
                        data-provider-id="<?= $provider['id'] ?>"
                        style="--brand-color: <?= htmlspecialchars($provider['brand_color']) ?>;">
                        <img src="../assets/icons/<?= htmlspecialchars($provider['icon']) ?>" alt="<?= htmlspecialchars($provider['name']) ?>">
                        <span><?= htmlspecialchars($provider['name']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Purchase Tabs -->
        <div class="tabs mb-6">
            <div class="tab-buttons d-flex">
                <button class="tab-btn active" data-tab="self" type="button">Pay For Self</button>
                <button class="tab-btn" data-tab="others" type="button">Pay For Others</button>
            </div>
            <!-- Plans Section -->
            <div class="tab-content position-relative active">
                <div id="planCards" class="row">
                    <!-- Plan cards will be dynamically loaded here -->
                </div>

                <!-- IUC/Smartcard Number -->
                <div class="mt-2" id="iucNumberWrap">
                    <input
                        type="text"
                        id="iucNumber"
                        name="iuc_number"
                        maxlength="10"
                        inputmode="numeric"
                        pattern="\d*"
                        placeholder="IUC/Smartcard Number"
                        class="input"
                        required
                        autocomplete="off">
                </div>

                <!-- Recipient Phone (for Pay For Others) -->
                <div class="input-group-container mt-2" id="recipientPhoneWrap" style="display: none;">
                    <span class="input-group-prefix text-xs">
                        <img src="../assets/img/ng.png" alt=""> +234
                    </span>
                    <input type="tel" id="recipientPhone" name="recipient_phone" maxlength="11"
                        placeholder="Phone Number" class="input phone-input" required>
                </div>

                <button type="button" class="btn w-100 mt-3 primary-btn" id="purchaseBtn" disabled>Purchase</button>
            </div>
        </div>

        <!-- Confirm Modal -->
        <div id="confirmModal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Payment</h5>
                    <button class="close-btn" id="closeConfirm" type="button">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-sm text-secondary mb-1 text-center">IUC/Smartcard</p>
                    <div id="customer-iuc" class="fw-bold fs-3 primary text-center mb-3" data-raw=""></div>
                    <div class="info-row"><span>Service:</span><span id="confirmService" class="fw-bold"></span></div>
                    <div class="info-row"><span>Plan:</span><span id="confirmPlan" class="fw-bold"></span></div>
                    <div class="info-row"><span>Amount:</span><span id="confirmAmount" class="fw-bolder primary fs-6"></span></div>
                    <div class="info-row"><span>Validity:</span><span id="confirmValidity" class="fw-bold"></span></div>
                    <div class="info-row"><span>Phone:</span><span id="confirmPhone" class="fw-bold"></span></div>
                    <div class="info-row">
                        <span>Customer Name:</span>
                        <span id="customerName" class="fw-bold">
                            <span class="spinner" id="customerNameSpinner" style="display: none;"></span>
                        </span>
                    </div>
                    <div class="info-row" id="accountStatusRow" style="display: none;">
                        <span>Account Status:</span><span id="accountStatus" class="fw-bold"></span>
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
        document.addEventListener("DOMContentLoaded", function() {
            // --- Element references ---
            const networkTabs = document.querySelectorAll(".service-tab");
            const planCardsContainer = document.getElementById("planCards");
            const iucNumberInput = document.getElementById("iucNumber");
            const recipientPhoneWrap = document.getElementById("recipientPhoneWrap");
            const recipientPhoneInput = document.getElementById("recipientPhone");
            const purchaseBtn = document.getElementById("purchaseBtn");
            const confirmModal = document.getElementById("confirmModal");
            const closeConfirm = document.getElementById("closeConfirm");
            const customerIUC = document.getElementById("customer-iuc");
            const confirmService = document.getElementById("confirmService");
            const confirmPlan = document.getElementById("confirmPlan");
            const confirmAmount = document.getElementById("confirmAmount");
            const confirmValidity = document.getElementById("confirmValidity");
            const confirmPhone = document.getElementById("confirmPhone");
            const customerName = document.getElementById("customerName");
            const customerNameSpinner = document.getElementById("customerNameSpinner");
            const accountStatusRow = document.getElementById("accountStatusRow");
            const accountStatus = document.getElementById("accountStatus");
            const payBtn = document.getElementById("payBtn");

            // --- State ---
            const firstTab = document.querySelector(".service-tab");
            let selectedTab = firstTab?.dataset.network || "";
            let selectedProviderId = firstTab?.dataset.providerId || "";
            let selectedPlan = null;
            let buyFor = "self";

            iucNumberInput.addEventListener("input", function() {
                const iuc = this.value.trim();
                const network = selectedTab;

                // Remove all non-digit characters
                this.value = this.value.replace(/\D/g, '');

                if (iuc.length === 10 && network) {
                    // Show spinner and hide customer name
                    customerNameSpinner.style.display = "inline-block";
                    customerName.textContent = "";
                    accountStatusRow.style.display = "none";

                    sendAjaxRequest(
                        "verify-iuc.php",
                        "POST",
                        `iuc=${encodeURIComponent(iuc)}&network=${encodeURIComponent(network)}`,
                        function(response) {
                            // Hide spinner
                            customerNameSpinner.style.display = "none";

                            if (response.success) {
                                customerName.textContent = response.customer_name;
                                accountStatus.textContent = response.account_status;
                                accountStatusRow.style.display = "flex";
                            } else {
                                customerName.textContent = "Unknown";
                                customerName.className = 'text-warning';
                                accountStatusRow.style.display = "none";
                            }
                            checkPurchaseReady();
                        }
                    );
                } else {
                    customerNameSpinner.style.display = "none";
                    customerName.textContent = "Unknown";
                    customerName.className = 'text-warning';
                    accountStatusRow.style.display = "none";
                    checkPurchaseReady();
                }
            });

            // --- Service selection ---
            networkTabs.forEach(tab => {
                tab.addEventListener("click", function() {
                    networkTabs.forEach(t => t.classList.remove("selected-tab", "active"));
                    tab.classList.add("selected-tab", "active");
                    selectedTab = tab.dataset.network;
                    selectedProviderId = tab.dataset.providerId;
                    selectedPlan = null;
                    highlightSelectedPlan();
                    purchaseBtn.disabled = true;
                    loadPlans();
                });
            });

            // --- Tab selection (Pay for Self/Others) ---
            document.querySelectorAll(".tab-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("active"));
                    btn.classList.add("active");
                    buyFor = btn.dataset.tab;
                    recipientPhoneWrap.style.display = buyFor === "others" ? "flex" : "none";
                    selectedPlan = null;
                    highlightSelectedPlan();
                    purchaseBtn.disabled = true;
                });
            });

            // --- Load plans via AJAX ---
            function loadPlans() {
                if (!selectedProviderId) return;
                planCardsContainer.innerHTML = '<div class="d-flex justify-content-center py-4"><span class="loading-spinner"></span></div>';
                sendAjaxRequest(
                    "fetch-tv-plans.php",
                    "POST",
                    `provider_id=${encodeURIComponent(selectedProviderId)}`,
                    function(response) {
                        if (response.success && Array.isArray(response.plans)) {
                            renderPlans(response.plans);
                        } else {
                            planCardsContainer.innerHTML = '<div class="text-center fw-bold text-sm text-danger py-4">No plans found.</div>';
                        }
                    }
                );
            }

            // --- Render plans ---
            function renderPlans(plans) {
                planCardsContainer.innerHTML = "";
                plans.forEach((plan, idx) => {
                    const card = document.createElement("div");
                    card.className = "col-4";
                    card.innerHTML = `
                            <div class="plan-card" data-plan-id="${plan.plan_id}" data-price="${plan.price}" data-name="${plan.name}" data-validity="${plan.validity}">
                                <div class="">${plan.plan_name}</div>
                                <div class="text-muted small">${plan.validity}</div>
                                <div class="fw-bold mt-2">₦${Number(plan.price).toLocaleString()}</div>
                            </div>
                        `;
                    card.querySelector(".plan-card").addEventListener("click", function() {
                        // Remove highlight from all plan cards in this container
                        document.querySelectorAll("#planCards .plan-card").forEach(c => {
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
                            name: plan.plan_name,
                            validity: plan.validity
                        };
                        checkPurchaseReady();
                    });
                    planCardsContainer.appendChild(card);
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
                const iucValid = /^\d{10}$/.test(iucNumberInput.value.trim());
                const phoneValid = buyFor === "self" || (buyFor === "others" && recipientPhoneInput.value.trim().length >= 10);
                if (selectedPlan && iucValid && phoneValid) {
                    purchaseBtn.disabled = false;
                } else {
                    purchaseBtn.disabled = true;
                }
            }

            // --- Input validation ---
            iucNumberInput.addEventListener("input", checkPurchaseReady);
            recipientPhoneInput.addEventListener("input", checkPurchaseReady);

            // --- Purchase button: show confirm modal ---
            purchaseBtn.addEventListener("click", function() {
                // Fill confirm modal
                const phone = buyFor === "self" ? "<?= $loggedInPhone ?>" : recipientPhoneInput.value.trim();
                const selectedTabEl = document.querySelector('.service-tab.selected-tab');


                // Validate selected plan
                if (!selectedPlan) {
                    showToasted("Please select a subscription plan.", "error");
                    return;
                }

                // Validate IUC number
                const iuc = iucNumberInput.value.trim();
                if (!/^\d{10}$/.test(iuc)) {
                    showToasted("Please enter a valid IUC number.", "error");
                    return;
                }

                const providerName = selectedTabEl ? selectedTabEl.querySelector('span').textContent : '';
                const providerIcon = selectedTabEl ? selectedTabEl.querySelector('img').src : '';

                // Show provider icon and name
                confirmService.innerHTML = providerIcon ?
                    `<img src="${providerIcon}" alt="${providerName}" style="height:22px;vertical-align:middle;">` :
                    providerName;

                const IUCNumber = iucNumberInput.value.trim();
                customerIUC.textContent = formatNumber(IUCNumber);
                customerIUC.setAttribute("data-raw", iucNumberInput.value.trim());

                // Show both plan name and volume if available
                let planDisplay = selectedPlan.name;
                if (selectedPlan.volume && selectedPlan.volume !== selectedPlan.name) {
                    planDisplay += ` (${selectedPlan.volume})`;
                }

                confirmPlan.textContent = selectedPlan ? selectedPlan.name : '';

                confirmAmount.textContent = `₦${Number(selectedPlan.price).toLocaleString()}`;
                confirmValidity.textContent = selectedPlan.validity ?? 'N/A';
                confirmPhone.textContent = phone;
                confirmModal.style.display = "flex";
            });

            // --- Pay button: show pinpad modal ---
            payBtn.addEventListener("click", function() {
                const pinpadModal = document.getElementById("pinpadModal");
                pinpadModal.dataset.amount = selectedPlan.price;
                pinpadModal.dataset.phone = buyFor === "self" ? "<?= $loggedInPhone ?>" : recipientPhoneInput.value.trim();
                pinpadModal.dataset.iuc = iucNumberInput.value.trim();
                pinpadModal.dataset.network = selectedTab;
                pinpadModal.dataset.type = selectedPlan.name + " (" + selectedPlan.validity + ")";
                pinpadModal.dataset.action = "tv";
                pinpadModal.dataset.plan_id = selectedPlan.plan_id;

                let rawAmount = pinpadModal.dataset.amount.replace(/,/g, '');

                sendAjaxRequest("check-balance.php", "POST", `amount=${rawAmount}`, function(response) {
                    if (response.success) {
                        pinpadModal.style.display = "flex";
                    } else {
                        showToasted(response.message, "error");
                        // log users balance
                        console.log("Failed", response.balance);
                    }
                });

                confirmModal.style.display = "none";
            });


            function formatNumber(num) {
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

    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>