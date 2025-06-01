<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container py-4">
        <header class="mb-4 page-header">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h5 class="fw-bold">KYC</h5>
            <span></span>
        </header>

        <div class="bg-white border-0 rounded shadow-xl px-4 py-3 my-4 animate-fade-in cursor-pointer" style="max-width:600px;margin:auto;">
            <!-- KYC Notice -->
            <div class="mb-3">
                <h6 class="fw-bold text-center">Know Your Customer (KYC)</h6>
                <p class="text-secondary small">To ensure the security and integrity of our platform, we require all users to complete the KYC process. Please update your account with your BVN or NIN.</p>
            </div>
        </div>

        <div class="tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" data-tab="nin">NIN</button>
                <button class="tab-btn" data-tab="pin">BVN</button>
            </div>
        </div>

        <div class="form-container mx-auto">
            <form id="KYCForm" method="post" novalidate>
                <!-- NIN Tab -->
                <div class="tab-content active" id="nin-tab">
                    <div class="mb-3">
                        <input type="text" placeholder="NIN" id="nin" name="nin"
                            class="input" maxlength="10" pattern="\d{10}" inputmode="numeric" required>
                    </div>
                </div>

                <!-- BVN Tab -->
                <div class="tab-content" id="pin-tab">
                    <div class="mb-3">
                        <input type="text" placeholder="BVN" id="bvn" name="bvn" class="input"
                            pattern="\d{10}" maxlength="10" inputmode="numeric" required>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn primary-btn w-100">
                        <span class="button-text text-uppercase">Update Password</span>
                    </button>
                </div>
            </form>
        </div>


        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamcodes 2025. All rights reserved.
    </footer>

    <script src="../assets/js/ajax.js"></script>
    <script>
    
    document.addEventListener("DOMContentLoaded", () => {
        const tabsContainer = document.querySelector(".tabs");
        const tabButtons = tabsContainer.querySelectorAll(".tab-btn");
        const contents = document.querySelectorAll(".tab-content");
        const form = document.getElementById("KYCForm");
        const submitBtn = form.querySelector('button[type="submit"]');
        const buttonText = submitBtn.querySelector(".button-text");

        // Activate first tab
        let activeTab = document.querySelector(".tab-btn.active")?.dataset.tab || tabButtons[0].dataset.tab;
        activateTab(activeTab);

        tabButtons.forEach(button => {
            button.addEventListener("click", () => {
                activeTab = button.dataset.tab;
                activateTab(activeTab);
                buttonText.textContent = `Update ${capitalize(activeTab)}`;
            });
        });

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            const activeInputs = document.querySelectorAll(`#${activeTab}-tab input`);
            for (const input of activeInputs) {
                if (!input.checkValidity()) {
                    showToasted(`Please fill the ${input.placeholder} field correctly.`, "error");
                    input.focus();
                    return;
                }
            }

            const data = Array.from(activeInputs).map(input =>
                `${encodeURIComponent(input.name)}=${encodeURIComponent(input.value)}`
            ).join("&");

            sendAjaxRequest("verify-kyc.php", "POST", data, (res) => {
                if (res.success) {
                    showToasted(res.message, "success");
                    form.reset();
                    setTimeout(() => {
                        window.location.href = "dashboard.php";
                    }, 2000);
                } else {
                    // Show toasted error message
                    showToasted(res.message, "error");

                }
            });
        });

        function activateTab(tabId) {
            tabButtons.forEach(btn =>
                btn.classList.toggle("active", btn.dataset.tab === tabId)
            );

            contents.forEach(content =>
                content.classList.toggle("active", content.id === `${tabId}-tab`)
            );

            buttonText.textContent = `Update ${capitalize(tabId)}`;
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>