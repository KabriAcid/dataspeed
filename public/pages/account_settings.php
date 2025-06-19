<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container-fluid py-5">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">Profile</h5>
                <div class="notification-icon">
                    <a href="notifications.php">
                        <i class="ni ni-bell-55 fs-5 text-gradient text-dark"></i>
                        <span class="notification-badge"></span>
                    </a>
                </div>
            </div>
        </header>

        <!-- User Info -->
        <div class="text-center">
            <img src="<?= $user['photo'] ?>" alt="User Photo" class="avatar border-0 avatar-xl shadow">
            <h4 class="fw-bold mt-2"><?= $user['user_name'] ?? $user['first_name']; ?>
                <?php if ($user['kyc_status'] == 'verified'): ?>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.4898 2 2 6.4898 2 12C2 17.5102 6.4898 22 12 22C17.5102 22 22 17.5102 22 12C22 6.4898 17.5102 2 12 2ZM15.5714 10.4694L11.4898 14.551C11.2857 14.6531 11.1837 14.7551 10.9796 14.7551C10.7755 14.7551 10.5714 14.6531 10.4694 14.551L8.42857 12.5102C8.12245 12.2041 8.12245 11.6939 8.42857 11.3878C8.73469 11.0816 9.2449 11.0816 9.55102 11.3878L11.0816 12.9184L14.6531 9.34694C14.9592 9.04082 15.4694 9.04082 15.7755 9.34694C15.8776 9.7551 15.8776 10.1633 15.5714 10.4694Z" fill="#030D45" />
                    </svg>
                <?php endif; ?>
            </h4>
        </div>

        <!-- Account Settings -->
        <div class="mt-5">
            <p class="text-muted fw-bold">Account Settings</p>
            <div class="mb-3">
                <a href="profile.php">
                    <div class="d-flex justify-content-between align-items-top">
                        <div class="d-flex justify-content-between align-items-top">
                            <div class="icon-container">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4.271 18.3457C4.271 18.3457 6.50002 15.5 12 15.5C17.5 15.5 19.7291 18.3457 19.7291 18.3457M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM15 9C15 10.6569 13.6569 12 12 12C10.3431 12 9 10.6569 9 9C9 7.34315 10.3431 6 12 6C13.6569 6 15 7.34315 15 9Z"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div class="mx-3">
                                <p class="m-0 fw-semibold">Profile Setting</p>
                                <span class="m-0 text-sm text-secondary">Profile update and biodata.</span>
                            </div>
                        </div>
                        <div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                        </div>
                    </div>
                </a>
            </div>


            <div class="mb-3">
                <a href="transactions.php">
                    <div class="d-flex justify-content-between align-items-top">
                        <div class="d-flex justify-content-between align-items-top">
                            <div class="icon-container">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21.1679 8C19.6248 4.46819 16.1006 2 12 2C6.81465 2 2.5511 5.94668 2.04938 11M22 3V7.4C22 7.73137 21.7314 8 21.4 8H17M2.88146 16C4.42458 19.5318 7.94874 22 12.0494 22C17.2347 22 21.4983 18.0533 22 13M2.04932 21V16.6C2.04932 16.2686 2.31795 16 2.64932 16H7.04932"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div class="mx-3">
                                <p class="m-0 fw-semibold">Transaction History</p>
                                <span class="m-0 text-sm text-secondary">View all history of transactions.</span>
                            </div>
                        </div>
                        <div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                        </div>
                    </div>
                </a>
            </div>

            <div class="mb-3">
                <a href="referrals.php">
                    <div class="d-flex justify-content-between align-items-top">
                        <div class="d-flex justify-content-between align-items-top">
                            <div class="icon-container">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 10H20M20 10H23M20 10V7M20 10V13M1 20V19C1 15.134 4.13401 12 8 12M15 20V19C15 15.134 11.866 12 8 12M8 12C10.2091 12 12 10.2091 12 8C12 5.79086 10.2091 4 8 4C5.79086 4 4 5.79086 4 8C4 10.2091 5.79086 12 8 12Z" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="mx-3">
                                <p class="m-0 fw-semibold">Referrals</p>
                                <span class="m-0 text-sm text-secondary">Claim pending referrals rewards.</span>
                            </div>
                        </div>
                        <div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                        </div>
                    </div>
                </a>
            </div>

            <div class="mb-3">
                <a href="password_pin_setting.php?tab=password">
                    <div class="d-flex justify-content-between align-items-top">
                        <div class="d-flex justify-content-between align-items-top">
                            <div class="icon-container">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 12C10 14.2091 8.20914 16 6 16C3.79086 16 2 14.2091 2 12C2 9.79086 3.79086 8 6 8C8.20914 8 10 9.79086 10 12ZM10 12H22V15" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M18 12V15" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div class="mx-3">
                                <p class="m-0 fw-semibold">Password & PIN</p>
                                <span class="m-0 text-sm text-secondary text-center">Change your password or PIN.</span>
                            </div>
                        </div>
                        <div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                        </div>
                    </div>
                </a>
            </div>
            <div class="mb-3">
                <a href="security_settings.php">
                    <div class="d-flex justify-content-between align-items-top">
                        <div class="d-flex justify-content-between align-items-top">
                            <div class="icon-container">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 12H17.4C17.7314 12 18 12.2686 18 12.6V19.4C18 19.7314 17.7314 20 17.4 20H6.6C6.26863 20 6 19.7314 6 19.4V12.6C6 12.2686 6.26863 12 6.6 12H8M16 12V8C16 6.66667 15.2 4 12 4C8.8 4 8 6.66667 8 8V12M16 12H8"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div class="mx-3">
                                <p class="m-0 fw-semibold">Security Settings</p>
                                <span class="m-0 text-sm text-secondary">Biometrics, hide balance, security.</span>
                            </div>
                        </div>
                        <div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                        </div>
                    </div>
                </a>
            </div>

            <p class="text-muted fw-bold mt-4">Others</p>
            <div class="mb-3">
                <a href="faq.php">
                    <div class="d-flex justify-content-between align-items-top">
                        <div class="d-flex justify-content-between align-items-top">
                            <div class="icon-container">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 12.5C17.2761 12.5 17.5 12.2761 17.5 12C17.5 11.7239 17.2761 11.5 17 11.5C16.7239 11.5 16.5 11.7239 16.5 12C16.5 12.2761 16.7239 12.5 17 12.5Z"
                                        fill="black" />
                                    <path
                                        d="M12 12.5C12.2761 12.5 12.5 12.2761 12.5 12C12.5 11.7239 12.2761 11.5 12 11.5C11.7239 11.5 11.5 11.7239 11.5 12C11.5 12.2761 11.7239 12.5 12 12.5Z"
                                        fill="black" />
                                    <path
                                        d="M7 12.5C7.27614 12.5 7.5 12.2761 7.5 12C7.5 11.7239 7.27614 11.5 7 11.5C6.72386 11.5 6.5 11.7239 6.5 12C6.5 12.2761 6.72386 12.5 7 12.5Z"
                                        fill="black" />
                                    <path
                                        d="M17 12.5C17.2761 12.5 17.5 12.2761 17.5 12C17.5 11.7239 17.2761 11.5 17 11.5C16.7239 11.5 16.5 11.7239 16.5 12C16.5 12.2761 16.7239 12.5 17 12.5Z"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12 12.5C12.2761 12.5 12.5 12.2761 12.5 12C12.5 11.7239 12.2761 11.5 12 11.5C11.7239 11.5 11.5 11.7239 11.5 12C11.5 12.2761 11.7239 12.5 12 12.5Z"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M7 12.5C7.27614 12.5 7.5 12.2761 7.5 12C7.5 11.7239 7.27614 11.5 7 11.5C6.72386 11.5 6.5 11.7239 6.5 12C6.5 12.2761 6.72386 12.5 7 12.5Z"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 13.8214 2.48697 15.5291 3.33782 17L2.5 21.5L7 20.6622C8.47087 21.513 10.1786 22 12 22Z"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div class="mx-3">
                                <p class="m-0 fw-semibold">FAQ & Help</p>
                                <span class="m-0 text-sm text-secondary">See frequently asked questions.</span>
                            </div>
                        </div>
                        <div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                        </div>
                    </div>
                </a>
            </div>
            <div class="mb-3">
                <a href="logout.php">
                    <div class="d-flex justify-content-between align-items-top">
                        <div class="d-flex justify-content-between align-items-top">
                            <div class="icon-container">
                                <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <title>spaceship</title>
                                    <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g id="Rounded-Icons" transform="translate(-1720.000000, -592.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                            <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                                <g id="spaceship" transform="translate(4.000000, 301.000000)">
                                                    <path class="color-background" d="M39.3,0.706666667 C38.9660984,0.370464027 38.5048767,0.192278529 38.0316667,0.216666667 C14.6516667,1.43666667 6.015,22.2633333 5.93166667,22.4733333 C5.68236407,23.0926189 5.82664679,23.8009159 6.29833333,24.2733333 L15.7266667,33.7016667 C16.2013871,34.1756798 16.9140329,34.3188658 17.535,34.065 C17.7433333,33.98 38.4583333,25.2466667 39.7816667,1.97666667 C39.8087196,1.50414529 39.6335979,1.04240574 39.3,0.706666667 Z M25.69,19.0233333 C24.7367525,19.9768687 23.3029475,20.2622391 22.0572426,19.7463614 C20.8115377,19.2304837 19.9992882,18.0149658 19.9992882,16.6666667 C19.9992882,15.3183676 20.8115377,14.1028496 22.0572426,13.5869719 C23.3029475,13.0710943 24.7367525,13.3564646 25.69,14.31 C26.9912731,15.6116662 26.9912731,17.7216672 25.69,19.0233333 L25.69,19.0233333 Z"></path>
                                                    <path class="color-background" d="M1.855,31.4066667 C3.05106558,30.2024182 4.79973884,29.7296005 6.43969145,30.1670277 C8.07964407,30.6044549 9.36054508,31.8853559 9.7979723,33.5253085 C10.2353995,35.1652612 9.76258177,36.9139344 8.55833333,38.11 C6.70666667,39.9616667 0,40 0,40 C0,40 0,33.2566667 1.855,31.4066667 Z" id="Path"></path>
                                                    <path class="color-background" d="M17.2616667,3.90166667 C12.4943643,3.07192755 7.62174065,4.61673894 4.20333333,8.04166667 C3.31200265,8.94126033 2.53706177,9.94913142 1.89666667,11.0416667 C1.5109569,11.6966059 1.61721591,12.5295394 2.155,13.0666667 L5.47,16.3833333 C8.55036617,11.4946947 12.5559074,7.25476565 17.2616667,3.90166667 L17.2616667,3.90166667 Z" id="color-2" opacity="0.598539807"></path>
                                                    <path class="color-background" d="M36.0983333,22.7383333 C36.9280725,27.5056357 35.3832611,32.3782594 31.9583333,35.7966667 C31.0587397,36.6879974 30.0508686,37.4629382 28.9583333,38.1033333 C28.3033941,38.4890431 27.4704606,38.3827841 26.9333333,37.845 L23.6166667,34.53 C28.5053053,31.4496338 32.7452344,27.4440926 36.0983333,22.7383333 L36.0983333,22.7383333 Z" id="color-3" opacity="0.598539807"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="mx-3">
                                <p class="m-0 fw-semibold">Sign out</p>
                                <span class="m-0 text-sm text-secondary">Log out of your account.</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </main>
    <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    <footer class="my-5">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamerscodes 2025. All rights reserved.</p>
    </footer>
    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>