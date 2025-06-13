<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
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
                <span></span>
            </div>
        </header>

        <!-- User Info -->
        <div class="text-center">
            <img src="<?= $user['photo'] ?>" alt="User Photo" class="avatar border-0 avatar-xl shadow">
            <h4 class="fw-bold mt-2"><?= $user['first_name'] ?? 'Guest'; ?>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon-container">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4.271 18.3457C4.271 18.3457 6.50002 15.5 12 15.5C17.5 15.5 19.7291 18.3457 19.7291 18.3457M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM15 9C15 10.6569 13.6569 12 12 12C10.3431 12 9 10.6569 9 9C9 7.34315 10.3431 6 12 6C13.6569 6 15 7.34315 15 9Z"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div class="">
                                <p class="m-0 mx-3 fw-semibold">Profile Setting</p>
                                <span class="m-0 mx-3 text-sm text-secondary">Profile update and biodata.</span>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon-container">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21.1679 8C19.6248 4.46819 16.1006 2 12 2C6.81465 2 2.5511 5.94668 2.04938 11M22 3V7.4C22 7.73137 21.7314 8 21.4 8H17M2.88146 16C4.42458 19.5318 7.94874 22 12.0494 22C17.2347 22 21.4983 18.0533 22 13M2.04932 21V16.6C2.04932 16.2686 2.31795 16 2.64932 16H7.04932"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div class="">
                                <p class="m-0 mx-3 fw-semibold">Transaction History</p>
                                <span class="m-0 mx-3 text-sm text-secondary no-wrap">View all history of transactions.</span>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon-container">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M22 9V17C22 18.1046 21.1046 19 20 19H4C2.89543 19 2 18.1046 2 17V7C2 5.89543 2.89543 5 4 5H20C21.1046 5 22 5.89543 22 7V9ZM22 9H6"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="">
                                <p class="m-0 mx-3 fw-semibold">Referrals</p>
                                <span class="m-0 mx-3 text-sm text-secondary no-wrap">Claim pending referrals rewards.</span>
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
                <a href="password-pin-settings.php?tab=password">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon-container">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 12H17.4C17.7314 12 18 12.2686 18 12.6V19.4C18 19.7314 17.7314 20 17.4 20H6.6C6.26863 20 6 19.7314 6 19.4V12.6C6 12.2686 6.26863 12 6.6 12H8M16 12V8C16 6.66667 15.2 4 12 4C8.8 4 8 6.66667 8 8V12M16 12H8"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div>
                                <p class="m-0 mx-3 fw-semibold">Password & PIN</p>
                                <span class="m-0 mx-3 text-sm text-secondary no-wrap">Change password and PIN.</span>
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
                <a href="security-settings.php">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon-container">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 12H17.4C17.7314 12 18 12.2686 18 12.6V19.4C18 19.7314 17.7314 20 17.4 20H6.6C6.26863 20 6 19.7314 6 19.4V12.6C6 12.2686 6.26863 12 6.6 12H8M16 12V8C16 6.66667 15.2 4 12 4C8.8 4 8 6.66667 8 8V12M16 12H8"
                                        stroke="#94241E" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div>
                                <p class="m-0 mx-3 fw-semibold">Security Settings</p>
                                <span class="m-0 mx-3 text-sm text-secondary no-wrap">Change password and PIN.</span>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
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
                            <div class="">
                                <p class="m-0 mx-3 fw-semibold">FAQ & Help</p>
                                <span class="m-0 mx-3 text-sm text-secondary no-wrap">See frequently asked questions.</span>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon-container">
                                <i class="ni ni-spaceship primary"></i>
                            </div>
                            <div class="">
                                <p class="m-0 mx-3 fw-semibold">Sign out</p>
                                <span class="m-0 mx-3 text-sm text-secondary no-wrap">Log out of your account.</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </main>
    <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    <footer class="my-5">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
    </footer>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>