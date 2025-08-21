<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

$loggedInPhone = isset($user['phone_number']) ? $user['phone_number'] : '';
$providers = getServiceProvider($pdo, 'electricity');

require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">Electricity Bills</h5>
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
                <button class="tab-btn active" data-tab="self" type="button">Pay For Self</button>
                <button class="tab-btn" data-tab="others" type="button">Pay For Others</button>
            </div>
            <div class="tab-content position-relative active" data-tab="self">
                <div class="input-group-container my-3">
                    <span class="input-group-prefix">Meter No</span>
                    <input type="text" class="input meter-input" id="meterNumberSelf" maxlength="12" placeholder="Enter Meter Number" inputmode="numeric" required>
                </div>
                <div class="input-group-container mb-3">
                    <span class="input-group-prefix">Type</span>
                    <select class="input meter-type-input" id="meterTypeSelf">
                        <option value="prepaid">Prepaid</option>
                        <option value="postpaid">Postpaid</option>
                    </select>
                </div>
                <div class="input-group-container mb-3">
                    <span class="input-group-prefix">₦</span>
                    <input type="number" class="input amount-input" id="amountSelf" min="1000" max="100000" placeholder="Enter Amount" required>
                </div>
                <button type="button" class="btn w-100 mt-3 primary-btn verify-btn" id="verifyBtnSelf">Verify Customer</button>
                <button type="button" class="btn w-100 mt-3 primary-btn pay-btn" id="payBtnSelf" disabled>Pay</button>
            </div>
            <div class="tab-content position-relative" data-tab="others">
                <div class="input-group-container my-3">
                    <span class="input-group-prefix">Meter No</span>
                    <input type="text" class="input meter-input" id="meterNumberOthers" maxlength="12" placeholder="Enter Meter Number" inputmode="numeric" required>
                </div>
                <div class="input-group-container mb-3">
                    <span class="input-group-prefix">Type</span>
                    <select class="input meter-type-input" id="meterTypeOthers">
                        <option value="prepaid">Prepaid</option>
                        <option value="postpaid">Postpaid</option>
                    </select>
                </div>
                <div class="input-group-container mb-3">
                    <span class="input-group-prefix">₦</span>
                    <input type="number" class="input amount-input" id="amountOthers" min="1000" max="100000" placeholder="Enter Amount" required>
                </div>
                <div class="input-group-container mb-3">
                    <span class="input-group-prefix text-xs">
                        <img src="../assets/img/ng.png" alt=""> +234
                    </span>
                    <input type="tel" id="phoneNumberOthers" maxlength="11" placeholder="Phone Number" class="input phone-input" required inputmode="numeric">
                </div>
                <button type="button" class="btn w-100 mt-3 primary-btn verify-btn" id="verifyBtnOthers">Verify Customer</button>
                <button type="button" class="btn w-100 mt-3 primary-btn pay-btn" id="payBtnOthers" disabled>Pay</button>
            </div>
        </div>

        <div id="customerDetails" class="mt-4" style="display:none;">
            <h5>Customer Details</h5>
            <p id="customerName"></p>
            <p id="customerAddress"></p>
        </div>

        <!-- Confirm Modal -->
        <div id="confirmModal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Payment</h5>
                    <button class="close-btn" id="closeConfirm" type="button">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="info-row"><span>Meter No:</span><span id="confirmMeter"></span></div>
                    <div class="info-row"><span>Type:</span><span id="confirmType"></span></div>
                    <div class="info-row"><span>Amount:</span><span id="confirmAmount"></span></div>
                    <div class="info-row"><span>Customer Name:</span><span id="confirmCustomerName"></span></div>
                    <div class="info-row"><span>Customer Address:</span><span id="confirmCustomerAddress"></span></div>
                </div>
                <div class="modal-footer">
                    <button class="pay-btn" id="payBtnModal" type="button">Pay</button>
                </div>
            </div>
        </div>

        <!-- Receipt Modal -->
        <div id="receiptModal" class="modal" tabindex="-1" style="display:none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment Receipt</h5>
                        <button type="button" class="btn-close" id="closeReceipt"></button>
                    </div>
                    <div class="modal-body" id="receiptBody"></div>
                </div>
            </div>
        </div>

        <!-- Overlay -->
        <div id="bodyOverlay" class="body-overlay" style="display: none;">
            <div class="overlay-spinner"></div>
        </div>

        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
        <?php require __DIR__ . '/../partials/pinpad.php' ?>
    </main>
    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/pinpad.js"></script>
    <script>
        // Tab switching logic
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                btn.classList.add('active');
                document.querySelector('.tab-content[data-tab="' + btn.dataset.tab + '"]').classList.add('active');
            });
        });

        // Service tab selection
        document.querySelectorAll('.service-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.service-tab').forEach(t => t.classList.remove('selected-tab'));
                tab.classList.add('selected-tab');
            });
        });

        // Verification and payment logic
        let verifiedData = {};

        function showBodyOverlay() {
            const overlay = document.getElementById('bodyOverlay');
            if (overlay) overlay.style.display = 'flex';
        }

        function hideBodyOverlay() {
            const overlay = document.getElementById('bodyOverlay');
            if (overlay) overlay.style.display = 'none';
        }

        function verifyCustomer(tabType) {
            const provider = document.querySelector('.service-tab.selected-tab').dataset.network;
            const meterType = document.getElementById('meterType' + tabType).value;
            const meterNumber = document.getElementById('meterNumber' + tabType).value.trim();
            if (!provider || !meterType || !meterNumber) {
                showToasted('Please fill all fields.', 'error');
                return;
            }
            showBodyOverlay();
            fetch('verify-electricity.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `service_id=${encodeURIComponent(provider)}&meter=${encodeURIComponent(meterNumber)}&type=${encodeURIComponent(meterType)}`
                })
                .then(res => res.json())
                .then(data => {
                    hideBodyOverlay();
                    if (data.success) {
                        verifiedData = data;
                        document.getElementById('customerDetails').style.display = 'block';
                        document.getElementById('customerName').textContent = 'Name: ' + (data.customer_name || '');
                        document.getElementById('customerAddress').textContent = 'Address: ' + (data.address || '');
                        document.getElementById('payBtn' + tabType).disabled = false;
                    } else {
                        showToasted(data.message, 'error');
                        document.getElementById('customerDetails').style.display = 'none';
                        document.getElementById('payBtn' + tabType).disabled = true;
                    }
                })
                .catch(() => {
                    hideBodyOverlay();
                    showToasted('Network error. Please try again.', 'error');
                });
        }
        document.getElementById('verifyBtnSelf').onclick = function() {
            verifyCustomer('Self');
        };
        document.getElementById('verifyBtnOthers').onclick = function() {
            verifyCustomer('Others');
        };

        // Confirm modal logic
        function showConfirmModal(tabType) {
            const meterNumber = document.getElementById('meterNumber' + tabType).value.trim();
            const meterType = document.getElementById('meterType' + tabType).value;
            const amount = document.getElementById('amount' + tabType).value;
            document.getElementById('confirmMeter').textContent = meterNumber;
            document.getElementById('confirmType').textContent = meterType;
            document.getElementById('confirmAmount').textContent = '₦' + Number(amount).toLocaleString();
            document.getElementById('confirmCustomerName').textContent = verifiedData.customer_name || '';
            document.getElementById('confirmCustomerAddress').textContent = verifiedData.address || '';
            document.getElementById('confirmModal').style.display = 'flex';
        }
        document.getElementById('payBtnSelf').onclick = function() {
            showConfirmModal('Self');
        };
        document.getElementById('payBtnOthers').onclick = function() {
            showConfirmModal('Others');
        };
        document.getElementById('closeConfirm').onclick = function() {
            document.getElementById('confirmModal').style.display = 'none';
        };
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) this.style.display = 'none';
        });

        // Payment logic
        document.getElementById('payBtnModal').onclick = function() {
            const activeTab = document.querySelector('.tab-content.active').dataset.tab;
            const provider = document.querySelector('.service-tab.selected-tab').dataset.network;
            const meterType = document.getElementById('meterType' + activeTab).value;
            const meterNumber = document.getElementById('meterNumber' + activeTab).value.trim();
            const amount = document.getElementById('amount' + activeTab).value;
            fetch('buy-electricity.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `service_id=${encodeURIComponent(provider)}&variation_id=${encodeURIComponent(meterType)}&meter=${encodeURIComponent(meterNumber)}&amount=${encodeURIComponent(amount)}`
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('confirmModal').style.display = 'none';
                    if (data.success) {
                        showReceipt(data);
                    } else {
                        showToasted(data.message, 'error');
                    }
                });
        };

        // Receipt modal logic
        function showReceipt(data) {
            let html = `
                <p><strong>Status:</strong> ${data.order_status}</p>
                <p><strong>Name:</strong> ${data.customer_name}</p>
                <p><strong>Address:</strong> ${data.address}</p>
                <p><strong>Meter Number:</strong> ${data.reference}</p>
                <p><strong>Amount:</strong> ₦${Number(data.amount).toLocaleString()}</p>
            `;
            if (data.token) html += `<p><strong>Token:</strong> ${data.token}</p>`;
            if (data.units) html += `<p><strong>Units:</strong> ${data.units}</p>`;
            if (data.band) html += `<p><strong>Band:</strong> ${data.band}</p>`;
            document.getElementById('receiptBody').innerHTML = html;
            document.getElementById('receiptModal').style.display = 'block';
        }
        document.getElementById('closeReceipt').onclick = function() {
            document.getElementById('receiptModal').style.display = 'none';
        };
    </script>
</body>

</html>