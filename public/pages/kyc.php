<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/initialize.php';

?>

<body>
    <main class="container py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">KYC</h5>
                <span></span>
            </div>
        </header>

        <div class="bg-white border-0 rounded shadow-xl px-4 py-3 my-4 animate-fade-in cursor-pointer" style="max-width:600px;margin:auto;">
            <!-- KYC Notice -->
            <div class="mb-3">
                <h6 class="fw-bold text-center">Know Your Customer (KYC)</h6>
                <p class="text-secondary small">
                    For your safety and to protect our community, we ask all users to verify their identity through the KYC (Know Your Customer) process. This helps prevent fraud, keeps your account secure, and ensures we meet legal requirements. Please update your account using your NIN or BVN.
                </p>

            </div>
        </div>

        <div class="tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" data-tab="nin">NIN</button>
                <button class="tab-btn" data-tab="bvn">BVN</button>
            </div>
        </div>
        <div class="form-container mx-auto">
            <form id="KYCForm" method="post" novalidate>
                <!-- NIN Tab -->
                <div class="tab-content active" id="nin-tab">
                    <div class="mb-3">
                        <input type="text" placeholder="NIN" id="nin" name="nin"
                            class="input" maxlength="11" pattern="\d{11}" inputmode="numeric" required>
                    </div>
                    <!--Verification consent toggle  -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="consent" name="nin-consent" required>
                        <label class="form-check-label text-sm" for="consent">
                            I consent to the verification of my KYC.
                        </label>

                    </div>
                    <div>
                        <button type="submit" id="nin-btn" class="btn primary-btn w-100" disabled>
                            <span class="button-text text-uppercase">Update NIN</span>
                        </button>
                    </div>
                </div>

                <!-- BVN Tab -->
                <div class="tab-content" id="bvn-tab">
                    <div class="mb-3">
                        <input type="text" placeholder="BVN" id="bvn" name="bvn"
                            class="input" maxlength="11" pattern="\d{11}" inputmode="numeric" required>
                    </div>
                    <!--Verification consent toggle  -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="consent" name="bvn-consent" required>
                        <label class="form-check-label text-sm" for="consent">
                            I consent to the verification of my KYC.
                        </label>

                    </div>
                    <div>
                        <button type="submit" id="bvn-btn" class="btn primary-btn w-100" disabled>
                            <span class="button-text text-uppercase">Update BVN</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- A modal that displays when the AJAX response is true -->
        <div class="modal fade" id="kycConfirmationModal" tabindex="-1" aria-labelledby="kycConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="kycConfirmationModalLabel">KYC Verification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="kycConfirmationMessage">Please confirm your KYC details.</p>
                        <div id="kycDetails"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="confirmKYC">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamerscodes 2025. All rights reserved.
    </footer>

    <script src="../assets/js/ajax.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const ninInput = document.getElementById("nin");
            const bvnInput = document.getElementById("bvn");
            const ninButton = document.getElementById("nin-btn");
            const bvnButton = document.getElementById("bvn-btn");
            const tabButtons = document.querySelectorAll(".tab-btn");
            const tabContents = document.querySelectorAll(".tab-content");

            // Enable/Disable buttons based on input length
            ninInput.addEventListener("input", () => {
                ninButton.disabled = ninInput.value.length !== 11;
            });

            bvnInput.addEventListener("input", () => {
                bvnButton.disabled = bvnInput.value.length !== 11;
            });

            // Tab switching logic
            tabButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    const targetTab = button.getAttribute("data-tab");
                    const targetContent = document.getElementById(`${targetTab}-tab`);

                    // Ensure the target content exists
                    if (!targetContent) {
                        console.error(`Tab content with ID "${targetTab}-tab" not found.`);
                        return;
                    }

                    // Remove active class from all buttons and contents
                    tabButtons.forEach((btn) => btn.classList.remove("active"));
                    tabContents.forEach((content) => content.classList.remove("active"));

                    // Add active class to the clicked button and corresponding content
                    button.classList.add("active");
                    targetContent.classList.add("active");
                });
            });

            // Form submission logic
            const form = document.getElementById("KYCForm");
            form.addEventListener("submit", function(e) {
                e.preventDefault();

                const activeTab = document.querySelector(".tab-content.active");
                const activeInputs = activeTab.querySelectorAll("input");
                for (const input of activeInputs) {
                    // Check if strings is not a number
                    if (!/^\d{11}$/.test(input.value)) {
                        showToasted("Please enter a valid 11-digit number.", "error");
                        input.focus();
                        return;
                    }
                    if (!input.checkValidity()) {
                        showToasted(`Please fill the ${input.placeholder} field correctly.`, "error");
                        input.focus();
                        return;
                    }
                }

                const data = Array.from(activeInputs).map(input =>
                    `${encodeURIComponent(input.name)}=${encodeURIComponent(input.value)}`
                ).join("&");

                sendAjaxRequest("verify-kyc.php", "POST", data, (response) => {
                    if (response.success) {
                        showToasted(response.message, "success");
                        form.reset();
                        ninButton.disabled = true;
                        bvnButton.disabled = true;

                        // Show the modal (Bootstrap 5)
                        document.getElementById('kycConfirmationModal').classList.add('show');
                    } else {
                        showToasted(response.message, "error");
                    }
                });
            });
        });
    </script>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>