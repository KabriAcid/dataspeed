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
            <h5 class="text-center fw-bold">Buy Airtime</h5>
        </header>

        <!-- Network Selection -->
        <div class="network-section ">
            <div class="network-tabs">
                <div class="network-tab selected-network" id="mtn-tab" data-network="mtn" data-provider-id="1" style="--brand-color: #ffcc00;">
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
            <!-- BFS -->
            <div class="tab-content active" data-tab="self">
                <div class="quick-amounts d-flex flex-wrap justify-content-between my-3 mx-1">
                <!-- add data-amount attribute to the buttons -->
                <button class="btn amount-btn" data-amount="100">₦100</button>
                <button class="btn amount-btn" data-amount="200">₦200</button>
                <button class="btn amount-btn" data-amount="500">₦500</button>
                <button class="btn amount-btn" data-amount="1000">₦1,000</button>
                <button class="btn amount-btn" data-amount="2000">₦2,000</button>
                <button class="btn amount-btn" data-amount="5000">₦5,000</button>
                </div>
                
                <div class="input-group-container">
                    <span class="input-group-prefix">₦</span>
                    <input type="text" class="input amount-input" placeholder="Enter Amount">
                </div>
                <button type="submit" class="btn w-100 mt-3 primary-btn purchase-btn"  disabled>Purchase</button>
            </div>
            
            <!-- BFO -->
            <div class="tab-content" data-tab="others">
                <div class="quick-amounts d-flex flex-wrap justify-content-between my-3 mx-1">
                     <button class="btn amount-btn" data-amount="100">₦100</button>
                    <button class="btn amount-btn" data-amount="200">₦200</button>
                    <button class="btn amount-btn" data-amount="500">₦500</button>
                    <button class="btn amount-btn" data-amount="1000">₦1,000</button>
                    <button class="btn amount-btn" data-amount="2000">₦2,000</button>
                    <button class="btn amount-btn" data-amount="5000">₦5,000</button>
                </div>
                
                <div class="input-group-container mb-3">
                    <span class="input-group-prefix">₦</span>
                    <input type="text" class="input amount-input" placeholder="Enter Amount">
                </div>
                
                <!-- Phone Number Input -->
                <div class="input-group-container">
                    <span class="input-group-prefix text-xs">
                        <img src="../assets/img/ng.png" alt=""> +234
                    </span>
                    <input type="tel" id="phone-number" name="phone_number" maxlength="10"
                        placeholder="Phone Number" class="input phone-input" required>
                </div>
                <button type="submit" class="btn w-100 mt-3 primary-btn purchase-btn"  disabled>Purchase</button>
            </div>

        </div>

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


    <!-- Bottom navigation -->
    <?php require __DIR__ . '/../partials/bottom-nav.php' ?>

    </main>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        let selectedNetwork = null;
        let selectedAmount = null;

        const networkTabs = document.querySelectorAll(".network-tab");
        const amountButtons = document.querySelectorAll(".amount-btn");
        const tabButtons = document.querySelectorAll(".tab-btn");
        const tabContents = document.querySelectorAll(".tab-content");
        const airtelLogo = document.querySelector(".airtel-logo");
        const confirmModal = document.getElementById("confirmModal");
        const closeConfirmBtn = document.getElementById("closeConfirm");
        const purchaseBtns = document.querySelectorAll(".purchase-btn");

        // --- Tab Switching ---
        tabButtons.forEach(btn => {
            btn.addEventListener("click", function () {
                tabButtons.forEach(b => b.classList.remove("active"));
                tabContents.forEach(c => c.classList.remove("active"));
                this.classList.add("active");
                document.querySelector(`.tab-content[data-tab="${this.dataset.tab}"]`).classList.add("active");
                validatePurchaseButton();
            });
        });

        // --- Network Selection ---
        networkTabs.forEach(tab => {
            tab.addEventListener("click", () => {
                networkTabs.forEach(t => t.classList.remove("selected-network"));
                tab.classList.add("selected-network");
                selectedNetwork = tab.getAttribute("data-network");
                let brandColor = getComputedStyle(tab).getPropertyValue("--brand-color");
                networkTabs.forEach(t => {
                    t.style.backgroundColor = t.classList.contains("selected-network") ? brandColor : "";
                    t.style.color = t.classList.contains("selected-network") ? "#fff" : "";
                    t.style.fontWeight = t.classList.contains("selected-network") ? "bold" : "normal";
                });
                airtelLogo.src = "../assets/icons/airtel-logo-1.svg";
                if (selectedNetwork === "airtel") airtelLogo.src = "../assets/icons/airtel-logo-2.svg";
                // Highlight selected amount button if any
                const activeAmountBtn = document.querySelector(".amount-btn.selected-amount");
                if (activeAmountBtn) {
                    activeAmountBtn.style.backgroundColor = brandColor;
                    activeAmountBtn.style.color = "#fff";
                } else {
                    amountButtons.forEach(btn => {
                        btn.style.backgroundColor = "";
                        btn.style.color = "";
                    });
                }
                validatePurchaseButton();
            });
        });

        // --- Amount Selection ---
        amountButtons.forEach(btn => {
            btn.addEventListener("click", function () {
                if (!selectedNetwork) {
                    showToasted("Please select a network first.", 'error');
                    this.classList.remove("selected-amount");
                    getActiveAmountInput().value = "";
                    validatePurchaseButton();
                    return;
                }
                // Set value in active tab's amount input
                const input = getActiveAmountInput();
                input.value = this.textContent.replace(/₦/, "").trim();
                // Reset previous selections
                amountButtons.forEach(b => {
                    b.classList.remove("selected-amount");
                    b.style.backgroundColor = "";
                    b.style.color = "";
                });
                this.classList.add("selected-amount");
                selectedAmount = input.value;
                // Brand color
                this.style.backgroundColor = getComputedStyle(document.querySelector(".selected-network")).getPropertyValue("--brand-color");
                this.style.color = "#fff";
                validatePurchaseButton();
            });
        });

        // --- Input Listeners ---
        document.querySelectorAll(".amount-input").forEach(input => {
            input.addEventListener("input", () => {
                selectedAmount = input.value.trim();
                validatePurchaseButton();
            });
        });
        document.querySelectorAll(".phone-input").forEach(input => {
            input.addEventListener("input", validatePurchaseButton);
        });

        // --- Purchase Button & Confirm Modal ---
        purchaseBtns.forEach(btn => {
            btn.addEventListener("click", function (e) {
                e.preventDefault(); // Prevent form submission if inside a form

                // Get the selected amount from the active tab's input or selected button
                const activeTab = getActiveTab();
                const amountInput = getActiveAmountInput();
                let amount = amountInput.value.trim();

                // If an amount-btn is selected, use its data-amount for formatting
                const selectedBtn = activeTab.querySelector('.amount-btn.selected-amount');
                if (selectedBtn) {
                    amount = selectedBtn.getAttribute('data-amount');
                }

                // Format amount for display (add ₦ if missing)
                const formattedAmount = amount ? `₦${Number(amount).toLocaleString()}` : "";

                // Set the amount in the confirm modal
                document.getElementById('confirm-amount').textContent = formattedAmount;

                // You can also set other fields here (plan, phone, etc.) as needed

                confirmModal.style.display = "flex";
            });
        });

        closeConfirmBtn.addEventListener("click", function () {
            confirmModal.style.display = "none";
        });

        // Hide modal when clicking outside modal-content
        confirmModal.addEventListener("click", function (e) {
            if (e.target === confirmModal) {
                confirmModal.style.display = "none";
            }
        });

        // --- Utility Functions ---
        function getActiveTab() {
            return document.querySelector('.tab-content.active');
        }
        function getActiveAmountInput() {
            return getActiveTab().querySelector('.amount-input');
        }
        function getActivePhoneInput() {
            return getActiveTab().querySelector('.phone-input');
        }
        function getActivePurchaseBtn() {
            return getActiveTab().querySelector('.purchase-btn');
        }

        function validatePurchaseButton() {
            const activeTab = getActiveTab();
            const amountInput = getActiveAmountInput();
            const phoneInput = getActivePhoneInput();
            const purchaseBtn = getActivePurchaseBtn();

            let valid = false;
            if (activeTab.dataset.tab === "others") {
                valid = amountInput.value.trim() && phoneInput && phoneInput.value.trim().length === 10;
            } else {
                valid = amountInput.value.trim();
            }
            purchaseBtn.disabled = !valid;
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>