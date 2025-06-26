<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../config/initialize.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold m-0 p-0">Security Settings</h5>
                <div class="notification-icon">
                    <a href="notifications.php">
                        <i class="ni ni-bell-55 fs-5 text-gradient text-dark"></i>
                        <span class="notification-badge"></span>
                    </a>
                </div>
            </div>
        </header>
        <div class="">
            <!-- Biometrics -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-top">
                    <div class="d-flex justify-content-between align-items-top">
                        <div class="icon-container bg-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 3.51555C8.4301 2.55827 10.1499 2 12 2C16.1031 2 19.5649 4.74572 20.6482 8.5M21 14V22M3 22V11C3 9.94809 3.18046 8.93834 3.51212 8M18 22V11.3C18 7.82061 15.3137 5 12 5C8.68629 5 6 7.82061 6 11.3V14M6 18V22M9 22V11.15C9 9.4103 10.3431 8 12 8C12.8653 8 13.645 8.38466 14.1926 9M15 14V22M12 18.5V22M12 11V14" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="mx-3">
                            <h6 class="m-0 fw-semibold">Biometrics</h6>
                            <span class="m-0 text-sm text-secondary text-wrap">Choose to sign in with biometrics.</span>
                        </div>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input mt-1" type="checkbox" id="withBiometrics" tabindex="3">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hide or show balance -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-top">
                    <div class="d-flex justify-content-between align-items-top">
                        <div class="icon-container bg-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 3L21 21M10.5 10.6771C10.1888 11.0297 10 11.4928 10 12C10 13.1046 10.8954 14 12 14C12.5072 14 12.9703 13.8112 13.3229 13.5M7.36185 7.56107C5.68002 8.73966 4.27894 10.4188 3 12C4.88856 14.991 8.2817 18 12 18C13.5499 18 15.0434 17.4772 16.3949 16.6508M12 6C16.0084 6 18.7015 9.1582 21 12C20.6815 12.5043 20.3203 13.0092 19.922 13.5" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="mx-3">
                            <h6 class="m-0 fw-semibold">Hide balance</h6>
                            <span class="m-0 text-sm text-secondary">Show or hide your balance.</span>
                        </div>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input mt-1" type="checkbox" id="withBiometrics" tabindex="3">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hide or show balance -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-top">
                    <div class="d-flex justify-content-between align-items-top">
                        <div class="icon-container bg-white">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.6213 12.1213L20.7426 10M20.7426 10L22.864 7.87868M20.7426 10L18.6213 7.87868M20.7426 10L22.864 12.1213M1 20V19C1 15.134 4.13401 12 8 12M15 20V19C15 15.134 11.866 12 8 12M8 12C10.2091 12 12 10.2091 12 8C12 5.79086 10.2091 4 8 4C5.79086 4 4 5.79086 4 8C4 10.2091 5.79086 12 8 12Z" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                        <div class="mx-3">
                            <h6 class="m-0 fw-semibold">Session Expiry</h6>
                            <span class="m-0 text-sm text-secondary">Re-enter your password after every idle 30mins.</span>
                        </div>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input mt-1" type="checkbox" id="sessionExpirySwitch"
                                <?= ($userSettings['session_expiry_enabled'] ?? 1) ? 'checked' : '' ?>>
                        </div>
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
        document.getElementById('sessionExpirySwitch').addEventListener('change', function() {
            const enabled = this.checked ? 1 : 0;
            sendAjaxRequest(
                'update-security_setting.php',
                'POST',
                'setting=session_expiry_enabled&value=' + enabled,
                function(response) {
                    if (response.success) {
                        showToasted('Session expiry setting updated.', 'success');


                    } else {
                        showToasted('Failed to update setting.', 'error');
                    }
                }
            );
        });
    </script>
    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>