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

        <div class="network-section">
            <div class="network-tabs">
                <div class="network-tab" id="mtn-tab" data-network="mtn" style="--brand-color: #ffcc00;">
                    <img src="../assets/icons/mtn_logo.svg" alt="mtn-logo">
                    <span>MTN</span>
                </div>
                <div class="network-tab" id="airtel-tab" data-network="airtel" style="--brand-color: #EB1922;">
                    <img src="../assets/icons/airtel-logo-1.svg" alt="airtel-logo" class="airtel-logo">
                    <span>Airtel</span>
                </div>
                <div class="network-tab" id="glo-tab" data-network="glo" style="--brand-color: #50B651;">
                    <img src="../assets/icons/glo_logo.svg" alt="glo-logo">
                    <span>Glo</span>
                </div>
                <div class="network-tab" id="9mobile-tab" data-network="9mobile" style="--brand-color: #D6E806;">
                    <img src="../assets/icons/9mobile_logo.svg" alt="9mobile-logo">
                    <span>9Mobile</span>
                </div>
            </div>
        </div>

          <!-- TABS -->
          <div class="tabs" style="width: 100%;">
            <!-- Top Tabs -->
            <div class="tab-buttons">
                <button class="tab-btn active" data-tab="self">Buy For Self</button>
                <button class="tab-btn" data-tab="others">Buy For Others</button>
            </div>

            <!-- Sub Tabs -->             <div class="sub-tab-buttons">
                <button class="sub-tab-btn active" data-sub="daily">Daily</button>
                <button class="sub-tab-btn" data-sub="weekly">Weekly</button>
                <button class="sub-tab-btn" data-sub="monthly">Monthly</button>
            </div>


            <!-- Tab Content -->
            <div class="tab-content" id="" class="position-relative">
                <div id="plan-cards" class="cards-grid">
                    
                </div>
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
        const brandColor = this.style.getPropertyValue("--brand-color");

        document.body.style.setProperty("--brand-color", brandColor);
        airtelLogo.src = selectedNetwork === "airtel"
            ? "../assets/icons/airtel-logo-2.svg"
            : "../assets/icons/airtel-logo-1.svg";

        document.querySelectorAll(".network-tab").forEach(tab => {
            tab.addEventListener("click", () => {
                // Reset background color for all tabs
                document.querySelectorAll(".network-tab").forEach(t => {
                    t.style.backgroundColor = "";
                });

                // Apply the brand color to the selected tab
                const brandColor = this.style.getPropertyValue("--brand-color");
                document.body.style.setProperty("--brand-color", brandColor);
            });
        });
    });

     document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".tabs").forEach(tabsContainer => {
            const buttons = tabsContainer.querySelectorAll(".tab-btn");
            const contents = tabsContainer.querySelectorAll(".tab-content");

            // Activate first tab if none is active
            const activeBtn = tabsContainer.querySelector(".tab-btn.active") || buttons[0];
            if (activeBtn) activeBtn.classList.add("active");

            const tabId = activeBtn.dataset.tab;
            contents.forEach(content => {
                content.classList.toggle("active", content.id === tabId);
            });

            // Add event listeners
            buttons.forEach(button => {
                button.addEventListener("click", () => {
                    const tabId = button.dataset.tab;

                    buttons.forEach(btn => btn.classList.remove("active"));
                    button.classList.add("active");

                    contents.forEach(content => {
                        content.classList.toggle("active", content.id ===
                            tabId);
                    });
                });
            });
        });
    });

  const subTabButtons = document.querySelectorAll(".sub-tab-btn");

  subTabButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
        // Toggle active class

        subTabButtons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      // Get selected plan (e.g., daily, weekly)
      const selectedPlan = btn.dataset.sub;

      // Prepare data
      const postData = `plan=${selectedPlan}`;

      // Send AJAX request
      sendAjaxRequest("fetch-plans.php", "POST", postData, (response) => {
        if (response.success) {
          document.querySelector("#completed").innerHTML = response.html;
        } else {
          showToasted(response.message, 'error');
        }
      });
    });
  });

  function loadPlans(providerId, type, brandColor) {
    const url = 'fetch-plans.php';
    const data = `provider_id=${encodeURIComponent(providerId)}&type=${encodeURIComponent(type)}`;

    sendAjaxRequest(url, 'POST', data, function (response) {
        const container = document.getElementById('plan-cards');
        container.innerHTML = ''; // Clear existing plans

        if (!response.success) {
            showToasted(response.message || 'Failed to load plans', 'error');
            return;
        }

        // Loop through plans and create cards
        response.plans.forEach(plan => {
            const card = document.createElement('div');
            card.classList.add('plan-card');
            card.style.backgroundColor = brandColor;
            card.dataset.apiId = plan.api_id;
            card.dataset.planId = plan.id;
            card.dataset.price = plan.price;

            card.innerHTML = `
                <div class="plan-name">${plan.name}</div>
                <div class="plan-price">₦${parseFloat(plan.price).toLocaleString()}</div>
            `;

            card.addEventListener('click', () => {
                proceedToSummary(plan); // Call the next step
            });

            container.appendChild(card);
        });
    });
}

</script>


<!-- FontAwesome CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>