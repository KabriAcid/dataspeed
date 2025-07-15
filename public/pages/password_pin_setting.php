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
                <h5 class="fw-bold">Security Settings</h5>
                <div class="notification-icon">
                    <a href="notifications.php">
                        <i class="ni ni-bell-55 fs-5 text-gradient text-dark"></i>
                        <span class="notification-badge"></span>
                    </a>
                </div>
            </div>
        </header>


        <div class="tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" data-tab="password">Password</button>
                <button class="tab-btn" data-tab="pin">PIN</button>
            </div>

        </div>
        <!--  -->

        <div class="form-container mx-auto">
            <form id="securityForm" method="post" novalidate>
                <!-- Password Tab -->
                <div class="tab-content active" id="password-tab">
                    <div>
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="mb-3 password-wrapper">
                            <input type="password" placeholder="Password" id="newPassword" name="newPassword" class="input" minlength="6" required>
                            <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                        </div>
                    </div>
                    <div>
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <div class="mb-3 password-wrapper">
                            <input type="password" placeholder="Re-Password" id="confirmPassword" name="confirmPassword" class="input" minlength="6" required>
                            <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                        </div>
                    </div>
                </div>

                <!-- PIN Tab -->
                <div class="tab-content" id="pin-tab">
                    <div>
                        <label for="newPin" class="form-label">New PIN</label>
                        <div class="mb-3 password-wrapper" style="position: relative;">
                            <input type="password" placeholder="PIN" id="newPin" name="newPin" class="input" pattern="\d{4}" maxlength="4" inputmode="numeric" required>
                            <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                        </div>
                    </div>
                    <div>
                        <label for="confirmPin" class="form-label">Confirm PIN</label>
                        <div class="mb-3 password-wrapper" style="position: relative;">
                            <input type="password" placeholder="Re-PIN" id="confirmPin" name="confirmPin" class="input" pattern="\d{4}" maxlength="4" inputmode="numeric" required>
                            <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                        </div>
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
        &copy; Dreamerscodes 2025. All rights reserved.
    </footer>
    <script src="../assets/js/toggle-password.js"></script>
    <script src="../assets/js/ajax.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const tabsContainer = document.querySelector(".tabs");
            const tabButtons = tabsContainer.querySelectorAll(".tab-btn");
            const contents = document.querySelectorAll(".tab-content");
            const form = document.getElementById("securityForm");
            const submitBtn = form.querySelector('button[type="submit"]');
            const buttonText = submitBtn.querySelector(".button-text");

            // Get tab from URL
            function getTabFromURL() {
                const params = new URLSearchParams(window.location.search);
                const tab = params.get('tab');
                return tab && ['password', 'pin'].includes(tab) ? tab : tabButtons[0].dataset.tab;
            }

            // Activate tab from URL or default
            let activeTab = getTabFromURL();
            activateTab(activeTab);

            tabButtons.forEach(button => {
                button.addEventListener("click", () => {
                    activeTab = button.dataset.tab;
                    activateTab(activeTab);
                    buttonText.textContent = `Update ${capitalize(activeTab)}`;
                });
                window.history.replaceState(null, '', `?tab=${activeTab}`);
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

                sendAjaxRequest("update-passcodes.php", "POST", data, (res) => {
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
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>