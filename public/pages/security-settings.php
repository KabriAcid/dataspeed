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
                <h5 class="fw-bold">Security Settings</h5>
                <span></span>
            </div>
        </header>
        <div class="form-container">
            <!-- Biometrics -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-container bg-white">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.271 18.3457C4.271 18.3457 6.50002 15.5 12 15.5C17.5 15.5 19.7291 18.3457 19.7291 18.3457M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM15 9C15 10.6569 13.6569 12 12 12C10.3431 12 9 10.6569 9 9C9 7.34315 10.3431 6 12 6C13.6569 6 15 7.34315 15 9Z"
                                    stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                        <div class="">
                            <h6 class="m-0 mx-3 fw-semibold">Biometrics</h6>
                            <span class="m-0 mx-3 text-sm text-secondary">Choose to sign in with biometrics.</span>
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
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-container bg-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 3L21 21M10.5 10.6771C10.1888 11.0297 10 11.4928 10 12C10 13.1046 10.8954 14 12 14C12.5072 14 12.9703 13.8112 13.3229 13.5M7.36185 7.56107C5.68002 8.73966 4.27894 10.4188 3 12C4.88856 14.991 8.2817 18 12 18C13.5499 18 15.0434 17.4772 16.3949 16.6508M12 6C16.0084 6 18.7015 9.1582 21 12C20.6815 12.5043 20.3203 13.0092 19.922 13.5" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="">
                            <h6 class="m-0 mx-3 fw-semibold">Hide balance</h6>
                            <span class="m-0 mx-3 text-sm text-secondary">Show or hide your balance.</span>
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
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-container bg-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 12H17.4C17.7314 12 18 12.2686 18 12.6V19.4C18 19.7314 17.7314 20 17.4 20H6.6C6.26863 20 6 19.7314 6 19.4V12.6C6 12.2686 6.26863 12 6.6 12H8M16 12V8C16 6.66667 15.2 4 12 4C8.8 4 8 6.66667 8 8V12M16 12H8" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="">
                            <h6 class="m-0 mx-3 fw-semibold">Session Expiry</h6>
                            <span class="m-0 mx-3 text-sm text-secondary">Re-enter your password after every idle 30mins.</span>
                        </div>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input mt-1" type="checkbox" id="withBiometrics" tabindex="3">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamcodes 2025. All rights reserved.
    </footer>

    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>