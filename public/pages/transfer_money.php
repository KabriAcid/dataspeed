<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold text-center">Transfer Money</h5>
                <span></span>
            </div>
        </header>

        <div class="text-center">
            <p class="text-sm text-secondary mb-0">Your available balance:</p>
            <h2>&#8358;<span id="user-balance"><?= getUserBalance($pdo, $user_id) ?></span></h2>
        </div>
        <div class="text-center">
            <p class="text-secondary mb-3 text-sm text-center">Transfer money to another user on Dataspeed</p>
        </div>
        <div class="form-container">
            <div class="form-row">
                <div class="mb-3">
                    <label for="beneficiary" class="form-label">Beneficiary Email</label>
                    <input type="text" id="email" name="email" class="input" placeholder="Enter beneficiary email" required>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <div class="input-group-container">
                        <span class="input-group-prefix">
                            &#8358;
                        </span>
                        <input type="text" id="amount" name="amount" maxlength="10"
                            placeholder="Amount" class="input" required inputmode="numeric">
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" id="transfer-button" class="btn primary-btn w-100">Transfer <i class="ms-1 ni ni-send"></i></button>
                </div>
            </div>
        </div>

        <!-- Confirm Modal -->
        <div id="confirmModal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Details</h5>
                    <button class="close-btn" id="closeConfirm">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-sm text-secondary mb-1 text-center">Send to</p>
                    <div id="confirm-email" class="fw-bold fs-5 primary text-center mb-2">example@email.com</div>
                    <div class="info-row">
                        <span>Name:</span>
                        <span id="confirm-name" class="fw-bold"></span>
                    </div>
                    <div class="info-row">
                        <span>Phone Number:</span>
                        <span id="confirm-phone"></span>
                    </div>
                    <div class="info-row">
                        <span>City:</span>
                        <span id="confirm-city"></span>
                    </div>
                    <div class="info-row">
                        <span>Amount:</span>
                        <span id="confirm-amount" class="fw-bolder primary fs-6"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="pay-btn" id="payBtn">Send</button>
                </div>
            </div>
        </div>

        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
        <?php require __DIR__ . '/../partials/pinpad.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamerscodes 2025. All rights reserved.
    </footer>
    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/pin-pad.js"></script>
    <script>
        // Place this in a <script> tag or your JS file after DOMContentLoaded
        const emailInput = document.getElementById('email');
        const amountInput = document.getElementById('amount');
        const transferBtn = document.getElementById('transfer-button');
        const availableBalance = parseFloat(document.getElementById('user-balance').textContent);
        const currentUserEmail = "<?= htmlspecialchars($user['email'] ?? '') ?>";

        function validateForm() {
            const email = emailInput.value.trim();
            const amount = amountInput.value.trim();
            transferBtn.disabled = !(email && amount && !isNaN(amount) && Number(amount) > 0);
        }

        emailInput.addEventListener('input', validateForm);
        amountInput.addEventListener('input', validateForm);
        validateForm(); // Initial state

        transferBtn.onclick = function() {
            const email = emailInput.value.trim();
            const amount = amountInput.value.trim();

            sendAjaxRequest("fetch-user.php", "POST", `email=${email}`, function(response) {
                if (response.success) {
                    // Fill modal fields
                    document.getElementById('confirm-email').textContent = response.data.email;
                    document.getElementById('confirm-name').textContent = response.data.first_name + " " + response.data.last_name;
                    document.getElementById('confirm-city').textContent = response.data.city ? response.data.city : 'N/A';
                    document.getElementById('confirm-phone').textContent = formatPhoneNumber(response.data.phone_number || 'N/A');
                    document.getElementById('confirm-amount').textContent = '₦' + Number(amount).toLocaleString() + '.00';

                    // Show modal
                    document.getElementById('confirmModal').style.display = 'flex';
                } else {
                    showToasted(response.message, "error");
                }
            });

        };

        document.getElementById('payBtn').onclick = function() {
            const email = emailInput.value.trim();
            const amount = amountInput.value.trim();
            const availableBalance = parseFloat(document.getElementById('user-balance').textContent.replace(/,/g, ''));

            if (email.toLowerCase() === currentUserEmail.toLowerCase()) {
                showToasted('You cannot transfer money to yourself.', 'error');
                return;
            }

            if (Number(amount) < 100) {
                showToasted('Minimum transfer amount is ₦100.', 'error');
                return;
            }

            if (Number(amount) > 500000) {
                showToasted('Maximum transfer amount is ₦500,000.', 'error');
                return;
            }

            if (Number(amount) > availableBalance) {
                showToasted('Insufficient balance.', 'error');
                return;
            }

            document.getElementById('closeConfirm').onclick = function() {
                document.getElementById('confirmModal').style.display = 'none';
            };

            // Hide confirm modal and show pin pad modal
            document.getElementById('confirmModal').style.display = 'none';
            const pinpadModal = document.getElementById('pinpadModal');
            pinpadModal.style.display = 'flex';

            // Set required attributes for transfer
            pinpadModal.dataset.email = email;
            pinpadModal.dataset.amount = amount;
            pinpadModal.dataset.action = 'transfer';

            // Clear any previous purchase-related attributes
            delete pinpadModal.dataset.phone;
            delete pinpadModal.dataset.network;
            delete pinpadModal.dataset.type;
        };

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
    </script>

    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>