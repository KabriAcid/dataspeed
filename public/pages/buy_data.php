<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';

?>


<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Data Bundles</h5>
        </header>

        <!-- Network Selection -->
        <div class="network-section">
            <div class="network-tabs">
                <div class="network-tab active" id="mtn-tab" data-network="mtn" data-provider-id="1" style="--brand-color: #ffcc00;">
                    <img src="../assets/icons/mtn_logo.svg" alt="MTN">
                    <span>MTN</span>
                </div>
                <div class="network-tab" id="airtel-tab" data-network="airtel" data-provider-id="2" style="--brand-color: #EB1922;">
                    <img src="../assets/icons/airtel-logo-1.svg" alt="Airtel" class="airtel-logo">
                    <span>Airtel</span>
                </div>
                <div class="network-tab" id="glo-tab" data-network="glo" data-provider-id="3" style="--brand-color: #4BB44E;">
                    <img src="../assets/icons/glo_logo.svg" alt="Glo">
                    <span>Glo</span>
                </div>
                <div class="network-tab" id="9mobile-tab" data-network="9mobile" data-provider-id="4" style="--brand-color: #D6E806;">
                    <img src="../assets/icons/9mobile_logo.svg" alt="9Mobile">
                    <span>9Mobile</span>
                </div>
            </div>
        </div>

<!-- Purchase Tabs -->
<div class="tabs">
    <div class="tab-buttons">
        <button class="tab-btn active" data-tab="self">Buy For Self</button>
        <button class="tab-btn" data-tab="others">Buy For Others</button>
    </div>

    <div class="sub-tab-buttons">
        <button class="sub-tab-btn active" data-sub="daily">Daily</button>
        <button class="sub-tab-btn" data-sub="weekly">Weekly</button>
        <button class="sub-tab-btn" data-sub="monthly">Monthly</button>
    </div>

    <!-- Plans Section -->
    <div class="tab-contet position-relative">
        <div id="plan-cards" class="cards-grid">
            <div class="row">
                <?php
                $plansQuery = $pdo->prepare("SELECT id, price, volume, validity FROM service_plans WHERE provider_id = 1 AND type = 'daily' AND is_active = 1");
                $plansQuery->execute();
                $plans = $plansQuery->fetchAll(PDO::FETCH_ASSOC);

                foreach ($plans as $plan): ?>
                    <div class="col-4">
                        <div class="plan-card" data-id="<?= $plan['id'] ?>" data-price="<?= $plan['price'] ?>" data-volume="<?= $plan['volume'] ?>" data-validity="<?= $plan['validity'] ?>">
                            <div class="data-price">₦<?= number_format($plan['price']) ?></div>
                            <div class="data-volume"><?= htmlspecialchars($plan['volume']) ?></div>
                            <div class="data-validity"><?= htmlspecialchars($plan['validity'] ?? '') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="button" class="btn w-100 mt-3 primary-btn" id="purchaseBtn" disabled>Purchase</button>
    </div>
</div>




<?php require __DIR__ . '/../partials/bottom-nav.php' ?>
</main>

<!-- Confirm Modal -->
<div id="confirmModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Confirm Purchase</h5>
            <button class="close-btn" id="closeConfirm">&times;</button>
        </div>
        <div class="modal-body">
            <p class="text-sm text-secondary mb-1 text-center">Send to</p>
            <div id="customer-phone" data-raw="">080********</div>
            <div class="info-row"><span>Network:</span><span id="confirm-network" class="">MTN</span></div>
            <div class="info-row"><span>Plan:</span><span id="confirm-plan" class="fw-bold">500MB</span></div>
            <div class="info-row"><span>Amount:</span><span id="confirm-amount" class="fw-bolder primary fs-6">₦100</span></div>
            <div class="info-row">
                <span>Product</span>
                <span><i class="icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 19.51L12.01 19.4989M2 8C8 3.5 16 3.5 22 8M5 12C9 8.99999 15 9 19 12M8.5 15.5C10.7504 14.1 13.2498 14.0996 15.5001 15.5"
                            stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </i> Internet Data</span>
            </div>
        </div>
        <div class="modal-footer">
            <button class="pay-btn" id="payBtn">Pay</button>
        </div>
    </div>
</div>


<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/pin-pad.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
    let selectedNetwork = null;
    let selectedPlan = null;

    const networkTabs = document.querySelectorAll(".network-tab");
    const planCards = document.querySelectorAll(".plan-card");
    const subTabs = document.querySelectorAll(".sub-tab-btn");
    const bfsTab = document.querySelector(".tab-btn[data-tab='self']");
    const bfoTab = document.querySelector(".tab-btn[data-tab='others']");
    const phoneInput = document.getElementById("recipientPhone");
    const purchaseBtn = document.getElementById("purchaseBtn");
    const airtelLogo = document.querySelector(".airtel-logo");

    // **1. Handle Network Selection**
    networkTabs.forEach(tab => {
        tab.addEventListener("click", () => {
            selectedNetwork = tab.dataset.network;

        // Get brand color
        let brandColor = getComputedStyle(tab).getPropertyValue("--brand-color");

        // Set global brand color
        document.body.style.setProperty("--brand-color", brandColor.trim());

        // Remove previous highlights
        networkTabs.forEach(t => {
            t.style.backgroundColor = "";
            t.classList.remove("active");

        });

        // Apply active styles
        tab.classList.add("active");
        tab.style.backgroundColor = brandColor.trim(); // **Fix: Apply BG color**
        // If Airtel is selected, update Airtel-specific background
        if (selectedNetwork === "airtel") {
            airtelLogo.src = selectedNetwork === "airtel"
                ? "../assets/icons/airtel-logo-2.svg"
                : "../assets/icons/airtel-logo-1.svg";
        }
        });
    });

    // **2. Prevent Plan Selection Until Network is Selected**
    planCards.forEach(card => {
        card.addEventListener("click", () => {
            if (!selectedNetwork) {
                showToasted("Please select a network first!", "info");
                return;
            }

            // Highlight selected plan
            planCards.forEach(c => c.classList.remove("selected-plan"));
            card.classList.add("selected-plan");

            selectedPlan = card;
            console.log("Plan selected:", selectedPlan.querySelector(".data-volume").innerText);
        });
    });

    // **3. Handle Sub-tab Selection & AJAX Request**
    subTabs.forEach(tab => {
        tab.addEventListener("click", () => {
            let subType = tab.getAttribute("data-sub");

            // Ensure BFS is active
            if (!bfsTab.classList.contains("active")) return;

            // Ensure Daily plan is selected
            if (subType !== "daily") return;

            // Ensure network & plan are selected
            if (!selectedNetwork && !selectedPlan) {
                showToasted("Select both a network and a plan first!", "success");
                return;
            }

            let userPhone = <?= $user['phone_number'] ?>;
            let planData = {
                provider_id: selectedNetwork,
                type: subType,
                price: selectedPlan.querySelector(".data-price").innerText,
                volume: selectedPlan.querySelector(".data-volume").innerText,
                validity: selectedPlan.querySelector(".data-validity").innerText,
                phone: userPhone
            };


            sendAjaxRequest("fetch-plan.php", planData, (response) => {
                if (response.error) {
                    showToasted(response.error, "error");
                } else {
                    updateTabContent(response.plans);
                }
            });
        });
    });

    // **4. Smooth Transition for Plan Updates**
    function updateTabContent(plans) {
        let container = document.getElementById("plan-cards");
        
        // Apply fade out effect
        container.style.opacity = 0;

        setTimeout(() => {
            container.innerHTML = ""; // Clear previous content

            plans.forEach(plan => {
                let card = document.createElement("div");
                card.className = "plan-card";
                card.innerHTML = `
                    <div class="data-price">₦${plan.price}</div>
                    <div class="data-volume">${plan.volume}</div>
                    <div class="data-validity">${plan.validity}</div>
                `;
                
                container.appendChild(card);
            });

            // Fade in new content
            container.style.opacity = 1;
        }, 300);
    }
    // **5. Handle BFO (Buy For Others)**
    bfoTab.addEventListener("click", () => {
        if (!selectedNetwork) {
            selectedNetwork == 'mtn'
        } else {
            showToasted("Select a network and a plan first!", "error");
            return;
        }

        // Show input field & activate purchase button
        phoneInput.style.display = "block";
    });

    purchaseBtn.addEventListener("click", () => {
        if (!selectedPlan || !phoneInput.value) {
            showToasted("Enter recipient phone number!", "error");
            return;
        }

        toggleConfirmModal();
    });
});

</script>

<!-- FontAwesome CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>