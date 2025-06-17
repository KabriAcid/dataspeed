<?php
session_start();

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
?>
<?php
$loggedInPhone = isset($user['phone_number']) ? $user['phone_number'] : '';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">Buy Airtime</h5>
                <div class="notification-icon">
                    <a href="notifications.php">
                        <i class="ni ni-bell-55 fs-5 text-gradient text-dark"></i>
                        <span class="notification-badge"></span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Service Selection -->
        <div class="service-section ">
            <div class="service-tabs">
                <div class="service-tab selected-tab" id="mtn-tab" data-network="mtn" data-provider-id="1" style="--brand-color: #FFCB05;">
                    <img src="../assets/icons/mtn_logo.svg" alt="MTN">
                    <span>MTN</span>
                </div>
                <div class="service-tab" id="airtel-tab" data-network="airtel" data-provider-id="2" style="--brand-color: #EB1922;">
                    <img src="../assets/icons/airtel-logo-1.svg" alt="Airtel" class="airtel-logo">
                    <span>Airtel</span>
                </div>
                <div class="service-tab" id="glo-tab" data-network="glo" data-provider-id="3" style="--brand-color: #4BB44E;">
                    <img src="../assets/icons/glo_logo.svg" alt="Glo">
                    <span>Glo</span>
                </div>
                <div class="service-tab" id="9mobile-tab" data-network="9mobile" data-provider-id="4" style="--brand-color: #D6E806;">
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
                <button type="submit" class="btn w-100 mt-3 primary-btn purchase-btn" disabled>Purchase</button>
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
                        placeholder="Phone Number" class="input phone-input" required value="8011111111">
                </div>
                <button type="submit" class="btn w-100 mt-3 primary-btn purchase-btn" disabled>Purchase</button>
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
                    <div class="info-row"><span>Service:</span><span id="confirm-network" class="">N/A</span></div>
                    <div class="info-row"><span>Type:</span><span id="confirm-plan" class="fw-bold">Self</span></div>
                    <div class="info-row"><span>Amount:</span><span id="confirm-amount" class="fw-bolder primary fs-6">₦0</span></div>
                    <div class="info-row">
                        <span>Product:</span>
                        <span>
                            <i class="icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 5C12.5523 5 13 4.55228 13 4C13 3.44772 12.5523 3 12 3C11.4477 3 11 3.44772 11 4C11 4.55228 11.4477 5 12 5ZM12 5L14.5 14M12 5L9.5 14M16 1C16 1 17.5 2 17.5 4C17.5 6 16 7 16 7M8 1C8 1 6.5 2 6.5 4C6.5 6 8 7 8 7M14.5 14H9.5M14.5 14L15.8889 19M9.5 14L8.11111 19M7 23L8.11111 19M8.11111 19H15.8889M15.8889 19L17 23"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </i> Airtime</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="pay-btn" id="payBtn">Pay</button>
                </div>
            </div>
        </div>


        <!-- Bottom navigation -->
        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
        <?php require __DIR__ . '/../partials/pinpad.php' ?>

    </main>
    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/pin-pad.js"></script>
    <script>
        // Set network SVG in confirm modal
        document.addEventListener("DOMContentLoaded", () => {
            const networkSVGs = {
                MTN: `<img src="../assets/img/icons/mtn.png" alt="MTN" style="height:25px;">`,
                AIRTEL: `<img src="../assets/img/icons/airtel.png" alt="Airtel" style="height:25px;">`,
                GLO: `<img src="../assets/img/icons/glo.png" alt="Glo" style="height:25px;">`,
                '9MOBILE': `<img src="../assets/img/icons/9mobile.png" alt="9Mobile" style="height:25px;">`
            };

            let selectedTab = null;
            let selectedAmount = null;

            const networkTabs = document.querySelectorAll(".service-tab");
            const amountButtons = document.querySelectorAll(".amount-btn");
            const tabButtons = document.querySelectorAll(".tab-btn");
            const tabContents = document.querySelectorAll(".tab-content");
            const airtelLogo = document.querySelector(".airtel-logo");

            // Confirm Modal Elements
            const confirmModal = document.getElementById("confirmModal");
            const closeConfirmBtn = document.getElementById("closeConfirm");
            const purchaseBtns = document.querySelectorAll(".purchase-btn");

            // Pin pad modal elements
            const payBtn = document.getElementById("payBtn");
            const pinpadModal = document.getElementById("pinpadModal");
            const closePinpad = document.getElementById("closePinpad");

            // --- Tab Switching ---
            tabButtons.forEach(btn => {
                btn.addEventListener("click", function() {
                    tabButtons.forEach(b => b.classList.remove("active"));
                    tabContents.forEach(c => c.classList.remove("active"));
                    this.classList.add("active");
                    document.querySelector(`.tab-content[data-tab="${this.dataset.tab}"]`).classList.add("active");
                    validatePurchaseButton();
                });
            });

            // --- Service Selection ---
            networkTabs.forEach(tab => {
                tab.addEventListener("click", () => {
                    networkTabs.forEach(t => t.classList.remove("selected-tab"));
                    tab.classList.add("selected-tab");
                    selectedTab = tab.getAttribute("data-network");
                    let brandColor = getComputedStyle(tab).getPropertyValue("--brand-color");
                    networkTabs.forEach(t => {
                        t.style.backgroundColor = t.classList.contains("selected-tab") ? brandColor : "";
                        t.style.color = t.classList.contains("selected-tab") ? "#fff" : "";
                        t.style.fontWeight = t.classList.contains("selected-tab") ? "bold" : "normal";
                    });
                    airtelLogo.src = "../assets/icons/airtel-logo-1.svg";
                    if (selectedTab === "airtel") airtelLogo.src = "../assets/icons/airtel-logo-2.svg";
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
                btn.addEventListener("click", function() {
                    if (!selectedTab) {
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
                    this.style.backgroundColor = getComputedStyle(document.querySelector(".selected-tab")).getPropertyValue("--brand-color");
                    this.style.color = "#fff";
                    validatePurchaseButton();
                });
            });

            // --- Input Listeners ---
            document.querySelectorAll(".amount-input").forEach(input => {
                input.addEventListener("input", function() {
                    selectedAmount = input.value.trim();
                    // Clear quick amount highlights if user types
                    amountButtons.forEach(b => {
                        b.classList.remove("selected-amount");
                        b.style.backgroundColor = "";
                        b.style.color = "";
                    });
                    validatePurchaseButton();
                });
            });
            
            document.querySelectorAll(".phone-input").forEach(input => {
                input.addEventListener("input", validatePurchaseButton);
            });

            // --- Purchase Button & Confirm Modal ---
            purchaseBtns.forEach(btn => {
                btn.addEventListener("click", function(e) {
                    e.preventDefault();

                    const activeTab = getActiveTab();
                    const amountInput = getActiveAmountInput();
                    const phoneInput = getActivePhoneInput();
                    let amount = amountInput.value.trim();

                    // Get selected amount from button if present
                    const selectedBtn = activeTab.querySelector('.amount-btn.selected-amount');
                    if (selectedBtn) {
                        amount = selectedBtn.getAttribute('data-amount');
                    }

                    // Get selected network
                    const selectedTabTab = document.querySelector('.service-tab.selected-tab');
                    const network = selectedTabTab ? selectedTabTab.getAttribute('data-network').toUpperCase() : '';

                    // Determine phone number for summary
                    let phone;
                    if (activeTab.dataset.tab === "self") {
                        phone = "<?php echo $loggedInPhone; ?>";
                    } else {
                        phone = phoneInput ? phoneInput.value.trim() : '';
                    }

                    // Set values in confirm modal
                    document.getElementById('customer-phone').textContent = phone ? formatPhoneNumber(phone) : 'N/A';
                    document.getElementById('customer-phone').setAttribute('data-raw', phone);
                    document.getElementById('confirm-network').innerHTML = networkSVGs[network] || '';
                    document.getElementById('confirm-plan').textContent = activeTab.dataset.tab === "self" ? "Self" : "Others";
                    document.getElementById('confirm-amount').textContent = formattedAmount(amount);

                    confirmModal.style.display = "flex";
                });
            });

            closeConfirmBtn.addEventListener("click", function() {
                confirmModal.style.display = "none";
            });

            // Hide modal when clicking outside modal-content
            confirmModal.addEventListener("click", function(e) {
                if (e.target === confirmModal) {
                    confirmModal.style.display = "none";
                }
            });

            // PIN PAD functionality
            payBtn.addEventListener("click", function() {
                let amountText = document.getElementById('confirm-amount').textContent;
                let rawAmount = amountText.replace(/[^\d]/g, '');
                let phone = document.getElementById('customer-phone').getAttribute('data-raw');
                let network = document.querySelector('.service-tab.selected-tab').getAttribute('data-network');
                let type = document.querySelector('.tab-content.active').dataset.tab === "self" ? "airtime_self" : "airtime_others";

                if (phone.length === 10) {
                    phone = '0' + phone;
                }

                // Set data attributes for the PIN pad modal
                pinpadModal.dataset.amount = rawAmount;
                pinpadModal.dataset.phone = phone;
                pinpadModal.dataset.network = network;
                pinpadModal.dataset.type = type;
                pinpadModal.dataset.action = 'airtime';


                sendAjaxRequest("check-balance.php", "POST", `amount=${rawAmount}`, function(response) {
                    if (response.success) {
                        pinpadModal.style.display = "flex";
                    } else {
                        showToasted(response.message, "error");
                    }
                });

                confirmModal.style.display = "none";
            });


            // closePinpad.addEventListener("click", function() {
            //     pinpadModal.style.display = "none";
            // });

            // Optional: Hide pinpad when clicking outside modal-content
            pinpadModal.addEventListener("click", function(e) {
                if (e.target === pinpadModal) {
                    pinpadModal.style.display = "none";
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

        function formattedAmount(amount) {
            return amount ? `₦${Number(amount).toLocaleString()}` : "";
        }
    </script>
    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>