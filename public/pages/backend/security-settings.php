<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <main class="container py-4">
        <header class="mb-4 text-center">
            <h5 class="fw-bold">Security Settings</h5>
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
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" placeholder="Password" id="newPassword" name="newPassword"
                            class="form-control" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" placeholder="Re-Password" id="confirmPassword" name="confirmPassword"
                            class="form-control" minlength="6" required>
                    </div>
                </div>

                <!-- PIN Tab -->
                <div class="tab-content" id="pin-tab">
                    <div class="mb-3">
                        <label for="newPin" class="form-label">New PIN</label>
                        <input type="password" placeholder="PIN" id="newPin" name="newPin" class="form-control"
                            pattern="\d{4}" maxlength="4" inputmode="numeric" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPin" class="form-label">Confirm PIN</label>
                        <input type="password" placeholder="Re-PIN" id="confirmPin" name="confirmPin"
                            class="form-control" pattern="\d{4}" maxlength="4" inputmode="numeric" required>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn primary-btn w-100">
                        <span class="button-text text-uppercase">Update Password</span>
                    </button>
                </div>
            </form>
        </div>

        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamcodes 2025. All rights reserved.
    </footer>

    <script>
    function sendAjaxRequest(url, method, data, callback) {
        if (!navigator.onLine) {
            callback({
                success: false,
                message: "You are offline. Please check your internet connection."
            });
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 0) {
                    callback({
                        success: false,
                        message: "Request failed. You may be offline or the server is unreachable."
                    });
                } else {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        callback(response);
                    } catch (error) {
                        callback({
                            success: false,
                            message: "Invalid JSON response"
                        });
                    }
                }
            }
        };


        xhr.onerror = function() {
            callback({
                success: false,
                message: "An error occurred during the request. Please check your internet connection."
            });
        };

        xhr.ontimeout = function() {
            callback({
                success: false,
                message: "Request timed out. Please check your internet connection and try again."
            });
        };

        xhr.timeout = 10000; // Optional: set timeout to 10 seconds
        xhr.send(data);
    }
    document.addEventListener("DOMContentLoaded", () => {
        const tabsContainer = document.querySelector(".tabs");
        const tabButtons = tabsContainer.querySelectorAll(".tab-btn");
        const contents = document.querySelectorAll(".tab-content");
        const form = document.getElementById("securityForm");
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

            sendAjaxRequest("update-passcodes.php", "POST", data, (res) => {
                showToasted(res.message, res.success ? "success" : "error");
                if (res.success) form.reset();
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