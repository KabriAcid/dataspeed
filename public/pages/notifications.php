<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';

$notifications = getUserNotifications($pdo, $user_id, 20);
$groupedNotifications = groupNotificationsByDate($notifications);

?>

<body>
    <main class="container py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">Notifications</h5>
                <span></span>
            </div>
        </header>

        <!-- Notifications List -->
        <div class="d-flex justify-content-center notifications-container">
            <div class="card h-100 p-0" style="max-width: 600px;width: 600px;">
                <div class="card-header pb-0 mb-0">
                    <p class="text-sm mb-0">
                        <span class="font-weight-bold">Today</span>
                    </p>
                </div>
                <div class="notifications-date-group">
                    <div class="card-body p-3">
                        <div class="timeline timeline-one-side">
                            <?php if (!empty($notifications)): ?>
                                <?php foreach ($notifications as $note): ?>
                                    <div class="timeline-block mb-3">
                                        <span class="timeline-step">
                                            <i class="<?= htmlspecialchars($note['icon']) ?> <?= htmlspecialchars($note['color'] ?? '') ?> text-gradient"></i>
                                        </span>
                                        <div class="timeline-content">
                                            <div class="">
                                                <p class="text-dark text-sm font-weight-bold mb-0">
                                                    <?= htmlspecialchars($note['title']) ?>
                                                </p>
                                                <span class="text-secondary font-weight-bold text-xs mt-1 mb-0" style="position: absolute;right: 0;top: 5px;">
                                                    <?= date('g:i A', strtotime($note['created_at'])) ?>
                                                </span>
                                            </div>
                                            <?php if (!empty($note['message'])): ?>
                                                <div class="text-xs mt-1"><?= htmlspecialchars($note['message']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-secondary py-4">No notifications found.</div>
                            <?php endif; ?>
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

    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>