<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';
require __DIR__ . '/../partials/header.php';

// Check if user is setting PIN for the first time
$user_id = $_SESSION['user_id'] ?? null;
$isFirstPin = false;
if ($user_id) {
    $stmt = $pdo->prepare("SELECT txn_pin FROM users WHERE user_id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $isFirstPin = empty($row['txn_pin']);
}
?>

<body>
    <main class="container py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center animate-fade-in">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg" role="button" tabindex="0" aria-label="Go back">
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


        <div class="tabs animate-fade-in" style="animation-delay: 80ms; animation-fill-mode: both;">
            <div class="tab-buttons">
                <button class="tab-btn active" data-tab="password">Password</button>
                <button class="tab-btn" data-tab="pin">PIN</button>
            </div>

        </div>
        <!--  -->

        <div class="form-container mx-auto animate-fade-in" style="animation-delay: 160ms; animation-fill-mode: both;">
            <form id="securityForm" method="post" novalidate>
                <!-- Password Tab -->
                <div class="tab-content active" id="password-tab">
                    <div>
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <div class="mb-3 password-wrapper">
                            <input type="password" placeholder="Current Password" id="currentPassword" name="currentPassword" class="input" minlength="6" required>
                            <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                        </div>
                    </div>
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
                    <?php if (!$isFirstPin): ?>
                        <div id="currentPinField">
                            <label for="currentPin" class="form-label">Current PIN</label>
                            <div class="mb-3 password-wrapper" style="position: relative;">
                                <input type="password" placeholder="Current PIN" id="currentPin" name="currentPin" class="input" pattern="\d{4}" maxlength="4" inputmode="numeric" required>
                                <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                            </div>
                        </div>
                    <?php endif; ?>
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
        <!-- Overlay -->
        <div id="bodyOverlay" class="body-overlay" style="display: none;">
            <div class="overlay-spinner"></div>
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
            // Header back button wiring (click + keyboard)
            const backBtn = document.querySelector('.header-back-button');
            if (backBtn) {
                backBtn.addEventListener('click', () => history.back());
                backBtn.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        history.back();
                    }
                });
            }
            const tabsContainer = document.querySelector(".tabs");
            const tabButtons = tabsContainer.querySelectorAll(".tab-btn");
            const contents = document.querySelectorAll(".tab-content");
            const form = document.getElementById("securityForm");
            const submitBtn = form.querySelector('button[type="submit"]');
            const buttonText = submitBtn.querySelector(".button-text");
            const overlay = document.getElementById('bodyOverlay');

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

                // If PIN tab and first time, skip currentPin validation
                let activeInputs = document.querySelectorAll(`#${activeTab}-tab input`);
                if (activeTab === "pin" && <?php echo json_encode($isFirstPin); ?>) {
                    activeInputs = Array.from(activeInputs).filter(input => input.name !== "currentPin");
                }
                for (const input of activeInputs) {
                    if (!input.checkValidity()) {
                        showToasted(`Please fill the ${input.placeholder} field correctly.`, "error");
                        input.focus();
                        return;
                    }
                }

                // Show overlay loader
                if (overlay) overlay.style.display = 'flex';
                submitBtn.disabled = true;

                const data = Array.from(activeInputs).map(input =>
                    `${encodeURIComponent(input.name)}=${encodeURIComponent(input.value)}`
                ).join("&");

                sendAjaxRequest("update-passcodes.php", "POST", data, (res) => {
                    // Hide overlay loader
                    if (overlay) overlay.style.display = 'none';
                    submitBtn.disabled = false;
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

                contents.forEach(content => {
                    const isActive = content.id === `${tabId}-tab`;
                    content.classList.toggle("active", isActive);
                    if (isActive) {
                        content.style.animation = 'fade-in 220ms ease';
                    }
                });

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