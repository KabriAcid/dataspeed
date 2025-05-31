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
                <input type="tel" id="phone-number" name="phone_number" maxlength="10" placeholder="Phone Number"
                    class="input">
            </div>
            <!-- Trigger Button -->
            <button type="button" class="btn w-100 mt-3 primary-btn" onclick="openModal()">Purchase</button>
        </form>


        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>
    
    <!-- Modal Structure -->
    <div id="confirmModal" class="modal-overlay">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <span class="modal-title">Confirm</span>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <p class="subtitle">Send to</p>
                <h2 class="phone-number" id="customer-phone">0906 789 5453</h2>

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
            <div class="info-row">
                <span>Amount</span>
                <span class="fw-bolder fs-6" id="service-amount">250</span>
            </div>
            <div class="info-row">
                <span>Data Package</span>
                <span class="fw-bold">1GB</span>
            </div>
            <div class="info-row">
                <span>Provider</span>
                <span>MTN AWOOF</span>
            </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
            <button class="pay-btn" onclick="submitPayment()" id="show-pin-modal">Pay</button>
            </div>
        </div>
    </div>

    <div id="pin-modal" class="modal-overlay">
        <div class="modal-content">
           <!-- Header -->
            <div class="modal-header">
                <span class="modal-title">PIN</span>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body">
                <h4 class="text-center">Welcome Back</h2>
                <div class="pin-section">
                    <p class="pin-instruction text-center">
                        <span class="check-icon">✓</span> Enter your PIN
                    </p>
                    <div class="pin-dots">
                        <div class="pin-dot" id="dot-1"></div>
                        <div class="pin-dot" id="dot-2"></div>
                        <div class="pin-dot" id="dot-3"></div>
                        <div class="pin-dot" id="dot-4"></div>
                    </div>
                </div>
                <!-- KEYPAD -->
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
                        <button class="key-button" data-value="0">0</button>
                        <button class="key-backspace" id="backspace">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
                        <path fill-rule="evenodd" d="M2.515 10.674a1.875 1.875 0 000 2.652L8.89 19.7c.352.351.829.549 1.326.549H19.5a3 3 0 003-3V6.75a3 3 0 00-3-3h-9.284c-.497 0-.974.198-1.326.55l-6.375 6.374zM12.53 9.22a.75.75 0 10-1.06 1.06L13.19 12l-1.72 1.72a.75.75 0 101.06 1.06l1.72-1.72 1.72 1.72a.75.75 0 101.06-1.06L15.31 12l1.72-1.72a.75.75 0 10-1.06-1.06l-1.72 1.72-1.72-1.72z" clip-rule="evenodd"/>
                        </svg>
                        </button>
                    </div>
                </div>
                <!-- FOOTER ACTIONS  -->
                <div class="footer-actions">
                    <button class="action-button logout">
                        ↩ Logout
                    </button>
                    <button class="action-button forgot shadow-sm">
                        ? Forgot PIN?
                    </button>
                </div>
            </div>
        </div>
    </div>

<script src="../assets/js/ajax.js"></script>
<script src="../assets/js/pin-pad.js"></script>
<script>
    document.querySelectorAll('.network-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            // Remove active state from all tabs
            document.querySelectorAll('.network-tab').forEach(t => {
                t.classList.remove('active');
                t.style.backgroundColor = ''; // Reset color
            });

            // Add active state to the clicked tab
            this.classList.add('active');
            const brandColor = this.style.getPropertyValue('--brand-color');
            this.style.backgroundColor = brandColor;
        });
    });

        document.getElementById('show-pin-modal').addEventListener('click', () => {
            document.getElementById('pin-modal').style.display = 'block';
            initPinPad('#pin-modal', function(pin) {
                console.log('PIN entered:', pin);
                // Continue AJAX request here...
            });
        });


    const amount = document.getElementById('service-amount').textContent;
    
    function openModal() {
        document.getElementById('confirmModal').style.display = 'flex';
        const phoneInput = document.getElementById('phone-number').value.trim();
        document.getElementById('customer-phone').textContent = phoneInput;
        document.getElementById('service-amount').textContent = ('₦' + amount + '.00');
    
    }

    
    function closeModal() {
    document.getElementById('confirmModal').style.display = 'none';
    document.getElementById('pin-modal').style.display = 'none';
}

    function showPinModal() {
        document.getElementById('pin-modal').style.display = 'flex';
    }
    
    function submitPayment() {
        sendAjaxRequest("check-balance.php", "POST", `amount=${encodeURIComponent(amount)}`, (response) => {
            if (response.success) {
                    showPinModal();
                    setTimeout(() => { 
                        console.log(response.balance)
                    }, TIMEOUT);
                } else {
                    showToasted(response.message, 'error');
                }
            });
        closeModal();
    }
    // document.addEventListener('click', () => {
    //     closeModal();
    // })
    </script>

<!-- FontAwesome CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>