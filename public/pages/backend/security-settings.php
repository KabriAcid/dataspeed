<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../partials/header.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>

<body>
    <main class="container py-4">
        <header class="mb-4 text-center">
            <h5 class="fw-bold">Security Settings</h5>
        </header>

        <div class="tabs mb-4 d-flex justify-content-center gap-3">
            <button class="tab-button btn btn-outline-primary active" data-tab="password">Password</button>
            <button class="tab-button btn btn-outline-primary" data-tab="pin">PIN</button>
        </div>

        <div class="form-container mx-auto" style="max-width: 400px;">
            <form id="securityForm" method="post" novalidate>
                <!-- Password Tab -->
                <div class="tab-content active" id="password-tab">
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" id="newPassword" name="newPassword" class="form-control" minlength="6"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control"
                            minlength="6" required>
                    </div>
                </div>

                <!-- PIN Tab -->
                <div class="tab-content" id="pin-tab" style="display:none;">
                    <div class="mb-3">
                        <label for="newPin" class="form-label">New PIN</label>
                        <input type="password" id="newPin" name="newPin" class="form-control" pattern="\d{4}"
                            maxlength="4" inputmode="numeric" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPin" class="form-label">Confirm PIN</label>
                        <input type="password" id="confirmPin" name="confirmPin" class="form-control" pattern="\d{4}"
                            maxlength="4" inputmode="numeric" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <span class="button-text">Update Password</span>
                </button>
            </form>
        </div>

        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamcodes 2025. All rights reserved.
    </footer>

    <script>
    function sendAjaxRequest(url, method, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
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
        };
        xhr.send(data);
    }

    // Tabs switching
    const tabs = document.querySelectorAll('.tab-button');
    const contents = document.querySelectorAll('.tab-content');
    const submitBtn = document.querySelector('button[type="submit"]');
    const buttonText = submitBtn.querySelector('.button-text');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            contents.forEach(content => content.style.display = 'none');
            const activeContent = document.getElementById(`${tab.dataset.tab}-tab`);
            activeContent.style.display = 'block';

            // Update submit button text
            buttonText.textContent =
                `Update ${tab.dataset.tab.charAt(0).toUpperCase() + tab.dataset.tab.slice(1)}`;
        });
    });

    document.getElementById('securityForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const activeTab = document.querySelector('.tab-button.active').dataset.tab;
        const inputs = document.querySelectorAll(`#${activeTab}-tab input`);

        // Client-side validation
        for (const input of inputs) {
            if (!input.checkValidity()) {
                alert(`Please fill the ${input.name} field correctly.`);
                input.focus();
                return;
            }
        }

        // Serialize data from active tab inputs
        const params = [];
        inputs.forEach(input => {
            params.push(encodeURIComponent(input.name) + '=' + encodeURIComponent(input.value));
        });
        const dataString = params.join('&');

        sendAjaxRequest('update-passcodes.php', 'POST', dataString, function(response) {
            alert(response.message);
            if (response.success) {
                e.target.reset();
            }
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>