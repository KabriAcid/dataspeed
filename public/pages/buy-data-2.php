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
            <h5 class="text-center fw-bold">Buy Data</h5>
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


        <div class="plans-section">
            <div class="plans-container">
                <?php

                // Fetch data service ID
                $serviceQuery = $pdo->prepare("SELECT id FROM services WHERE slug = 'data'");
                $serviceQuery->execute();
                $dataService = $serviceQuery->fetch(PDO::FETCH_ASSOC);

                if ($dataService) {
                    // Fetch active data plans
                    $plansQuery = $pdo->prepare("SELECT name, price, type FROM service_plans WHERE service_id = ? AND is_active = 1 LIMIT 2");
                    $plansQuery->execute([$dataService["id"]]);
                    $plans = $plansQuery->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($plans as $plan): ?>
                        <div class="plan-card" data-plan-id="<?= $plan['price']; ?>">
                            <div class="plan-details">
                                <div class="plan-price">₦<?= number_format($plan['price'], 2); ?></div>
                                <div class="plan-data"><?= htmlspecialchars($plan['name']); ?></div>
                                <div class="plan-validity"><?= htmlspecialchars($plan['type'] ?? 'N/A'); ?></div>
                            </div>
                        </div>
                    <?php endforeach;
                } else {
                    echo "<p class='text-center text-muted'>No data plans available.</p>";
                }
                ?>
            </div>

            <!-- See All Button Outside the Row -->
            <div class="see-all-card" id="seeAllBtn" data-bs-toggle="modal" data-bs-target="#allPlansModal">
                <span class="see-all-text">SEE ALL</span>
            </div>
        </div>

<form action="" method="post">
    <div class="input-group-container">
        <span class="input-group-prefix text-xs">
            <img src="../assets/img/ng.png" alt=""> +234
        </span>
        <input type="tel" id="phone-number" name="phone_number" maxlength="10"
            placeholder="Phone Number" class="input">
    </div>
    <!-- Trigger Button -->
    <button type="button" class="btn w-100 mt-3 primary-btn" id="purchaseBtn" disabled>Purchase</button>
</form>

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

<!-- PIN Modal -->
<div id="pinModal" class="modal-overlay" style="display: none;">
  <div class="pin-container">
      <div class="pin-header">
          <img src="<?= $user['photo'];?>" alt="avatar" class="pinpad-avatar">
          <h6>Abdullahi</h6>
      </div>
      <h3 style="text-align: center;">Welcome Back</h3>
      <div class="pin-field">
        <!-- Icon section -->
        <div class="icon-section">
          <i class="fas fa-shield-check check-icon fa-2x"></i>
          <p class="mt-2 mb-0 fw-bold text-center">Enter Transaction PIN</p>
        </div>
          <!-- PIN Dots -->
          <div class="pin-section">
            <div class="pin-dots">
              <div class="pin-dot"></div>
              <div class="pin-dot"></div>
              <div class="pin-dot"></div>
              <div class="pin-dot"></div>
            </div>
          </div>
      </div>
      <div class="pin-keypad">
          <div class="keypad-row">
              <button class="key-button" data-value="1">1</button>
              <button class="key-button" data-value="2">2</button>
              <button class="key-button" data-value="3">3</button>
          </div>
          <div class="keypad-row">
              <button class="key-button" data-value="4">4</button>
              <button class="key-button" data-value="5">5</button>
              <button class="key-button" data-value="6">6</button>
          </div>
          <div class="keypad-row">
              <button class="key-button" data-value="7">7</button>
              <button class="key-button" data-value="8">8</button>
              <button class="key-button" data-value="9">9</button>
          </div>
          <div class="keypad-row">
              <button class="key-spacer"></button>
              <button class="key-button" data-value="0">0</button>
              <button id="backspace" class="key-backspace">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="45" height="45"><path fill-rule="evenodd" d="M2.515 10.674a1.875 1.875 0 000 2.652L8.89 19.7c.352.351.829.549 1.326.549H19.5a3 3 0 003-3V6.75a3 3 0 00-3-3h-9.284c-.497 0-.974.198-1.326.55l-6.375 6.374zM12.53 9.22a.75.75 0 10-1.06 1.06L13.19 12l-1.72 1.72a.75.75 0 101.06 1.06l1.72-1.72 1.72 1.72a.75.75 0 101.06-1.06L15.31 12l1.72-1.72a.75.75 0 10-1.06-1.06l-1.72 1.72-1.72-1.72z" clip-rule="evenodd"></path></svg>
              </i></button>
          </div>
      </div>
      <div class="pin-action-buttons">
          <button id="pin-logout-btn">Logout</button>
          <button id="pin-forgot-btn">Forgot PIN</button>
      </div>
  </div>
</div>

<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/pin-pad.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const phoneInput = document.getElementById("phone-number");
    const purchaseBtn = document.getElementById("purchaseBtn");
    const confirmModal = document.getElementById("confirmModal");
    const closeConfirm = document.getElementById("closeConfirm");
    const payBtn = document.getElementById("payBtn");
    const customerPhone = document.getElementById("customer-phone");
    const networkTabs = document.querySelectorAll(".network-tab");
    const plansContainer = document.querySelector(".plans-container"); 
    const airtelLogo = document.querySelector(".airtel-logo");

    let selectedNetwork = null;
    let selectedPlan = null;

    // **Phone Number Validation**
    phoneInput.addEventListener("input", function () {
        let raw = this.value.replace(/\D/g, "").slice(0, 10);
        this.value = raw;
        purchaseBtn.disabled = raw.length !== 10;
    });

    // **Network Selection Handling**
    networkTabs.forEach(tab => {
        tab.addEventListener("click", function () {
            networkTabs.forEach(tab => tab.classList.remove("active"));
            this.classList.add("active");

            selectedNetwork = this.dataset.network;
            const brandColor = this.style.getPropertyValue("--brand-color");

            document.body.style.setProperty("--brand-color", brandColor);
            airtelLogo.src = selectedNetwork === "airtel"
                ? "../assets/icons/airtel-logo-2.svg"
                : "../assets/icons/airtel-logo-1.svg";

                console.log("Selected Network:", selectedNetwork);

            // **Reset previous selections when switching networks**
            selectedPlan = null;
            document.querySelectorAll(".plan-card").forEach(card => card.classList.remove("active"));

            // **Plan Selection Handling**
            planCard.addEventListener("click", function () {
                if (!selectedNetwork) {
                    showToasted("Please select a network first.", "error");
                    return;
                }

                document.querySelectorAll(".plan-card").forEach(card => card.classList.remove("active"));
                this.classList.add("active");

                selectedPlan = this.dataset.planId;
                this.style.backgroundColor = document.body.style.getPropertyValue("--brand-color");
            });

            plansContainer.appendChild(planCard);

            sendAjaxRequest("fetch-plans.php", "POST", `network=${selectedNetwork}`, function (response) {
                if (response.success) {
                    plansContainer.innerHTML = "";
                    response.plans.forEach(plan => {
                        const planCard = document.createElement("div");
                        planCard.className = "plan-card";
                        planCard.dataset.planId = plan.price;
                        planCard.innerHTML = `
                            <div class="plan-details">
                                <div class="plan-price">${plan.price}</div>
                                <div class="plan-data">${plan.name}</div>
                                <div class="plan-validity">${plan.type || 'N/A'}</div>
                            </div>
                        `;

                    });
                } else {
                    showToasted(response.message, "error");
                }
            });
        });
    });

    // **Show Confirm Modal**
    purchaseBtn.addEventListener("click", function () {
        if (!selectedNetwork || !selectedPlan || phoneInput.value.trim().length !== 10) {
            showToasted("Please complete all selections before proceeding.", "error");
            return;
        }

        customerPhone.textContent = formatPhoneNumber(phoneInput.value);
        customerPhone.dataset.raw = "0" + phoneInput.value;
        confirmModal.style.display = "flex";
    });

    closeConfirm?.addEventListener("click", () => confirmModal.style.display = "none");
    confirmModal.addEventListener("click", (e) => { if (e.target === confirmModal) confirmModal.style.display = "none"; });

    // **Pay Button Action**
    payBtn?.addEventListener("click", function () {
        const phone = customerPhone?.dataset?.raw || "";
        const amount = document.getElementById("confirm-amount")?.textContent.replace(/\D/g, "");

        if (!phone || !amount) {
            showToasted("Missing purchase details.", "error");
            return;
        }

        sendAjaxRequest("check-balance.php", "POST", `amount=${amount}`, function (res) {
            if (res.success) {
                document.getElementById("pinModal").style.display = "flex";
            } else {
                showToasted(res.message, "error");
            }
        });
    });

    // **Format Phone Number**
    function formatPhoneNumber(num) {
        return num.length === 10 ? "0" + num.substring(0, 3) + " " + num.substring(3, 7) + " " + num.substring(7) : num;
    }
});

</script>



<!-- FontAwesome CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>