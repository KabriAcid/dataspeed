<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">FAQ</h5>
                <div class="notification-icon">
                    <a href="notifications.php">
                        <i class="ni ni-bell-55 fs-5 text-gradient text-dark"></i>
                        <span class="notification-badge"></span>
                    </a>
                </div>
            </div>
        </header>

        <div class="description">
            <p>A FAQ or Frequently Asked Questions is a section for helps users find information quickly without
                needing to contact customer support!</p>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question">
                    <span>What is Dataspeed?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>Dataspeed is a performance-centric web app designed to help users track finances, manage
                        referrals, and monitor activity with real-time dashboards and smart analytics.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>How do I access Dataspeed?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>You can access Dataspeed via any modern web browser. Simply visit the official Dataspeed website
                        and log in with your account credentials.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Is Dataspeed free to use?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>Yes, Dataspeed is free for basic usage. Advanced tools, analytics, or premium features may
                        require a subscription or verified account access.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>How do I track my referrals?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>You can view and manage all your referrals from your referral dashboard. It shows pending,
                        completed, and claimed rewards in real time.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Can I customize notifications?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>Yes, Dataspeed allows you to toggle important notifications like reward updates, balance changes,
                        and new referral activity from the settings page.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>How often is my wallet updated?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>Your wallet and transaction data are updated instantly after successful actions like claiming
                        rewards, receiving bonuses, or making transfers.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>What devices support Dataspeed?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>Dataspeed is optimized for desktops, tablets, and mobile browsers. A dedicated mobile app version
                        is also in development for Android and iOS.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Can I suggest a feature or improvement?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>Absolutely! We're community-driven. You can submit feedback or feature requests directly from
                        your account dashboard or through our support page.</p>
                </div>
            </div>
        </div>



        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamerscodes 2025. All rights reserved.
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');

                question.addEventListener('click', () => {
                    // Close other open items
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                        }
                    });

                    // Toggle current item
                    item.classList.toggle('active');
                });
            });
        });
    </script>

    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>