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
            <h6 class="text-center fw-bold">Profile</h6>
        </header>

        <!-- User Info -->
        <div class="text-center">
            <img src="../<?= $_SESSION['user']['photo'] ?>" alt="User Photo" class="profile-avatar">
            <h4 class="fw-bold mt-2"><?= $username; ?></h4>
            <p class="text-muted"><?= $user_location; ?></p>
        </div>

        <!-- Account Settings -->
        <div class="main-body mt-5">
            <p class="text-muted fw-bold">Account Settings</p>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-container">
                            <svg width="18" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 17V16C1 12.134 4.13401 9 8 9M15 17V16C15 12.134 11.866 9 8 9M8 9C10.2091 9 12 7.20914 12 5C12 2.79086 10.2091 1 8 1C5.79086 1 4 2.79086 4 5C4 7.20914 5.79086 9 8 9Z" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                        <p class="m-0 mx-3">Profile Setting</p>
                    </div>
                    <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </div>
                </div>
            </div>


            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-container">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.1679 8C19.6248 4.46819 16.1006 2 12 2C6.81465 2 2.5511 5.94668 2.04938 11M22 3V7.4C22 7.73137 21.7314 8 21.4 8H17M2.88146 16C4.42458 19.5318 7.94874 22 12.0494 22C17.2347 22 21.4983 18.0533 22 13M2.04932 21V16.6C2.04932 16.2686 2.31795 16 2.64932 16H7.04932" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                        <p class="m-0 mx-3">Transaction History</p>
                    </div>
                    <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-container">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22 9V17C22 18.1046 21.1046 19 20 19H4C2.89543 19 2 18.1046 2 17V7C2 5.89543 2.89543 5 4 5H20C21.1046 5 22 5.89543 22 7V9ZM22 9H6" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                        <p class="m-0 mx-3">Payments</p>
                    </div>
                    <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </div>
                </div>
            </div>

            <div class="mb-3">
                <a href="change-password.php">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon-container">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 12H17.4C17.7314 12 18 12.2686 18 12.6V19.4C18 19.7314 17.7314 20 17.4 20H6.6C6.26863 20 6 19.7314 6 19.4V12.6C6 12.2686 6.26863 12 6.6 12H8M16 12V8C16 6.66667 15.2 4 12 4C8.8 4 8 6.66667 8 8V12M16 12H8" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </div>
                            <p class="m-0 mx-3">Password & PIN</p>
                        </div>
                        <div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                    </div>
                </a>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-container">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 12.5C17.2761 12.5 17.5 12.2761 17.5 12C17.5 11.7239 17.2761 11.5 17 11.5C16.7239 11.5 16.5 11.7239 16.5 12C16.5 12.2761 16.7239 12.5 17 12.5Z" fill="black" />
                                <path d="M12 12.5C12.2761 12.5 12.5 12.2761 12.5 12C12.5 11.7239 12.2761 11.5 12 11.5C11.7239 11.5 11.5 11.7239 11.5 12C11.5 12.2761 11.7239 12.5 12 12.5Z" fill="black" />
                                <path d="M7 12.5C7.27614 12.5 7.5 12.2761 7.5 12C7.5 11.7239 7.27614 11.5 7 11.5C6.72386 11.5 6.5 11.7239 6.5 12C6.5 12.2761 6.72386 12.5 7 12.5Z" fill="black" />
                                <path d="M17 12.5C17.2761 12.5 17.5 12.2761 17.5 12C17.5 11.7239 17.2761 11.5 17 11.5C16.7239 11.5 16.5 11.7239 16.5 12C16.5 12.2761 16.7239 12.5 17 12.5Z" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 12.5C12.2761 12.5 12.5 12.2761 12.5 12C12.5 11.7239 12.2761 11.5 12 11.5C11.7239 11.5 11.5 11.7239 11.5 12C11.5 12.2761 11.7239 12.5 12 12.5Z" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M7 12.5C7.27614 12.5 7.5 12.2761 7.5 12C7.5 11.7239 7.27614 11.5 7 11.5C6.72386 11.5 6.5 11.7239 6.5 12C6.5 12.2761 6.72386 12.5 7 12.5Z" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 13.8214 2.48697 15.5291 3.33782 17L2.5 21.5L7 20.6622C8.47087 21.513 10.1786 22 12 22Z" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                        <p class="m-0 mx-3">Chat & Help</p>
                    </div>
                    <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6L15 12L9 18" stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </div>
                </div>
            </div>
        </div>

        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
        <a href="logout.php" class="mt-5 btn btn-danger">Exit</a>
        <footer class="my-4">
            <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
        </footer>


    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>