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
                    <img src="../assets/icons/airtel-logo-1.svg" alt="airtel-logo">
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
            <div class="d-flex" style="justify-content: space-around; align-items: center;">
                <!-- Card  -->
                <div>
                    <div class="card p-0 plan-card border-0 mb-3 shadow">
                        <div class="card-body px-3 py-2 text-dark rounded-3">
                            <div class="text-center">
                                <p id="data-amount">&#8358;800</p>
                                <h5 id="data-volume">1.2GB</h5>
                                <p id="data-duration">7 DAYS</p>
                            </div>
                            <!-- <span class="badge bg-dark-subtle text-dark fw-semibold">AWOOF</span> -->
                        </div>
                    </div>
                </div>
                <!-- Card  -->
                <div>
                    <div class="card p-0 plan-card border-0 mb-3 shadow">
                        <div class="card-body px-3 py-2 text-dark rounded-3">
                            <div class="text-center">
                                <p id="data-amount">&#8358;800</p>
                                <h5 id="data-volume">1.2GB</h5>
                                <p id="data-duration">7 DAYS</p>
                            </div>
                            <!-- <span class="badge bg-dark-subtle text-dark fw-semibold">AWOOF</span> -->
                        </div>
                    </div>
                </div>
                <!-- Card  -->
                <div>
                    <div class="card p-0 plan-card border-0 mb-3 shadow">
                        <div class="card-body px-3 py-2 text-dark rounded-3">
                            <div class="text-center">
                                <p id="data-amount">&#8358;800</p>
                                <h5 id="data-volume">1.2GB</h5>
                                <p id="data-duration">7 DAYS</p>
                            </div>
                            <!-- <span class="badge bg-dark-subtle text-dark fw-semibold">AWOOF</span> -->
                        </div>
                    </div>
                </div>
                <!-- Card  -->
                <div>
                    <div class="card p-0 plan-card border-0 mb-3 shadow">
                        <div class="card-body px-3 py-2 text-dark rounded-3">
                            <div class="text-center">
                                <p id="data-amount">&#8358;800</p>
                                <h5 id="data-volume">1.2GB</h5>
                                <p id="data-duration">7 DAYS</p>
                            </div>
                            <!-- <span class="badge bg-dark-subtle text-dark fw-semibold">AWOOF</span> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <form action="" method="post">
    <div class="phone-container">
        <span class="phone-prefix text-sm">
            <img src="https://flagcdn.com/w40/ng.png" alt="Nigeria Flag"> +234
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

<!-- PIN Pad Modal -->
<div id="pinModal" class="modal-overlay" style="display: none;">
    <div class="pin-container">
        <!-- Avatar -->
        <div class="avatar-section d-flex justify-content-center align-items-center">
            <img src="../assets/img/avatar.jpg" alt="User Avatar" class="pinpad-avatar img-fluid">
        </div>
        <div class="my-3 text-center">
            <h5 class="mb-1">Welcome Back!</h5>
            <p>Please enter your 4-digit PIN</p>
        </div>
        <div class="pin-section">
            <div class="pin-dots">
                <div class="pin-dot"></div>
                <div class="pin-dot"></div>
                <div class="pin-dot"></div>
                <div class="pin-dot"></div>
            </div>
        </div>
        <div class="keypad">
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
                <div class="key-spacer"></div>
                <button class="key-button"></button>
                <button class="key-button" data-value="0">0</button>
                <button id="backspace" class="key-backspace">&larr;</button>
            </div>
        </div>
        <div class="modal-actions">
            <button class="logout">Logout</button>
            <button class="forgot">Forgot PIN?</button>
        </div>
    </div>
</div>

<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/pin-pad.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('phone-number');
    const purchaseBtn = document.getElementById('purchaseBtn');
    const confirmModal = document.getElementById('confirmModal');
    const closeConfirm = document.getElementById('closeConfirm');
    const payBtn = document.getElementById('payBtn');
    const customerPhone = document.getElementById('customer-phone');

    // Validate phone number length
    phoneInput.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '');
        if (raw.length > 10) raw = raw.slice(0, 10);
        this.value = raw;

        if (raw.length === 10) {
            purchaseBtn.disabled = false;
        } else {
            purchaseBtn.disabled = true;
        }
    });

    // Show confirm modal when phone is valid
    purchaseBtn.addEventListener('click', function () {
        const raw = phoneInput.value.trim();
        if (raw.length !== 10) {
            showToasted('Please enter a valid 10-digit phone number.', 'error');
            return;
        }

        const formatted = formatPhoneNumber(raw);
        customerPhone.textContent = formatted;
        customerPhone.dataset.raw = '0' + raw;
        confirmModal.style.display = 'flex';
    });

    // Close modal with close button
    closeConfirm?.addEventListener('click', () => {
        confirmModal.style.display = 'none';
    });

    // Dismiss modal on outside click
    confirmModal.addEventListener('click', (e) => {
        if (e.target === confirmModal) {
            confirmModal.style.display = 'none';
        }
    });

    // Pay button action
    payBtn?.addEventListener('click', function () {
        const phone = customerPhone?.dataset?.raw || '';
        const amount = document.getElementById('confirm-amount')?.textContent.replace(/\D/g, '');

        if (!phone || !amount) {
            showToasted('Missing purchase details.', 'error');
            return;
        }

        sendAjaxRequest('check-balance.php', 'POST', `amount=${amount}`, function (res) {
            if (res.success) {
                document.getElementById('pinModal').style.display = 'flex';
            } else {
                showToasted(res.message, 'error');
            }
        });
    });
});

// Phone number formatter (e.g. 09012345678 => 090 1234 5678)
function formatPhoneNumber(num) {
    if (num.length !== 10) return num;
    return '0' + num.substring(0, 3) + ' ' + num.substring(3, 7) + ' ' + num.substring(7);
}
</script>

<script>
    // Reusable pin modal trigger
    const showPinModal = (onPinSuccess) => {
        const modal = document.getElementById('pinModal');
        modal.style.display = 'flex';

        initPinPad('#pinModal', function(pin) {
            sendAjaxRequest('authenticate-pin.php', 'POST', 'pin=' + pin, function(res) {
                if (res.success) {
                    showToasted('PIN verified.', 'success');
                    modal.style.display = 'none';
                    onPinSuccess(); // continue
                } else {
                    showToasted(res.message || 'Invalid PIN.', 'error');
                }
            });
        });
    };
</script>


<!-- FontAwesome CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>