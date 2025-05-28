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
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');
        const submitBtn = document.querySelector('button[type="submit"]');
        const buttonText = submitBtn.querySelector('.button-text');
        const form = document.getElementById('securityForm');

        // Initialize: show first tab
        setActiveTab(tabs[0]);

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                setActiveTab(tab);
            });
        });

        function setActiveTab(tab) {
            // Activate tab button
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // Show correct content
            contents.forEach(c => c.classList.remove('active'));
            document.getElementById(`${tab.dataset.tab}-tab`).classList.add('active');

            // Update button text
            buttonText.textContent = `Update ${capitalize(tab.dataset.tab)}`;
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const activeTab = document.querySelector('.tab-btn.active').dataset.tab;
            const inputs = document.querySelectorAll(`#${activeTab}-tab input`);

            for (const input of inputs) {
                if (!input.checkValidity()) {
                    alert(`Please fill the ${input.name} field correctly.`);
                    input.focus();
                    return;
                }
            }

            const data = Array.from(inputs).map(input =>
                `${encodeURIComponent(input.name)}=${encodeURIComponent(input.value)}`
            ).join('&');

            sendAjaxRequest('update-passcodes.php', 'POST', data, function(response) {
                alert(response.message);
                if (response.success) {
                    form.reset();
                }
            });
        });

        function sendAjaxRequest(url, method, data, callback) {
            const xhr = new XMLHttpRequest();
            xhr.open(method, url, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        callback(response);
                    } catch (err) {
                        callback({
                            success: false,
                            message: 'Server error. Please try again.'
                        });
                    }
                }
            };
            xhr.send(data);
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>