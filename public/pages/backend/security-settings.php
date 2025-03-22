<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../partials/header.php';

// Check if user session exists
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['user_id'];
    $username = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];
    $user_location = $_SESSION['user']['city'] ?? 'Nigeria';
} else {
    header('Location: login.php');
}
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Security Settings</h5>
        </header>

        <div class="container-fluid">
            <div class="tabs">
                <!-- PASS BUTTON -->
                <button class="tab-button active" data-tab="password">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 12H17.4C17.7314 12 18 12.2686 18 12.6V19.4C18 19.7314 17.7314 20 17.4 20H6.6C6.26863 20 6 19.7314 6 19.4V12.6C6 12.2686 6.26863 12 6.6 12H8M16 12V8C16 6.66667 15.2 4 12 4C8.8 4 8 6.66667 8 8V12M16 12H8" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <span class="fw-bold">Password</span>
                </button>

                <!-- PIN BUTTON -->
                <button class="tab-button" data-tab="pin">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 12C14 14.2091 15.7909 16 18 16C20.2091 16 22 14.2091 22 12C22 9.79086 20.2091 8 18 8C15.7909 8 14 9.79086 14 12ZM14 12H2V15" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M6 12V15" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <span>PIN</span>
                </button>
            </div>
        </div>

        <div class="form-container">
            <form id="securityForm">
                <div class="tab-content active" id="password-tab">
                    <div class="form-group">
                        <label>New Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="newPassword" class="input" required />

                            <svg class="toggle-password" id="eye-toggle" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 13C6.6 5 17.4 5 21 13M9 14C9 15.6569 10.3431 17 12 17C13.6569 17 15 15.6569 15 14C15 12.3431 13.6569 11 12 11C10.3431 11 9 12.3431 9 14Z" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirmPassword" class="input" required />
                    </div>
                </div>

                <div class="tab-content" id="pin-tab">
                    <div class="form-group">
                        <label>New PIN</label>
                        <input type="password" name="newPin" inputmode="numeric" pattern="\d{4}" maxlength="4" class="input" required />
                    </div>
                    <div class="form-group">
                        <label>Confirm PIN</label>
                        <input type="password" name="confirmPin" inputmode="numeric" pattern="\d{4}" maxlength="4" class="input" required />
                    </div>
                </div>

                <button type="submit" class="btn primary-btn w-100">
                    <i class="fa fa-spinner fa-spin d-none" id="spinner-icon"></i>
                    <span class="button-text">Update Password</span>
                </button>
            </form>
        </div>


        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>
    <footer class="my-4">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
    </footer>

    <script>
        // Switch active tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

                button.classList.add('active');
                document.getElementById(`${button.dataset.tab}-tab`).classList.add('active');
                document.querySelector('.button-text').textContent = `Update ${button.dataset.tab.charAt(0).toUpperCase() + button.dataset.tab.slice(1)}`;
            });
        });

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = button.previousElementSibling;
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });

        // Form submission using traditional AJAX
        document.getElementById('securityForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const activeTab = document.querySelector('.tab-button.active').dataset.tab;
            const activeInputs = document.querySelectorAll(`#${activeTab}-tab input`);
            const formData = [];

            activeInputs.forEach(input => {
                formData.push(`${input.name}=${encodeURIComponent(input.value)}`);
            });

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update-passcodes.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message || 'Update successful!');
                    } catch (error) {
                        alert('An error occurred while processing your request.');
                    }
                }
            };
            xhr.send(formData.join('&'));
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>