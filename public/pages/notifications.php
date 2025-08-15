<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

$notifications = getUserNotifications($pdo, $user_id, 20);
$groupedNotifications = groupNotificationsByDate($notifications);

require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container py-5">
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
            <div class="card h-100 p-0" style="max-width: 600px; width: 100%;">
                <div class="card-header pb-0 mb-0">
                    <p class="text-sm mb-0">
                        <!-- <span class="font-weight-bold">Today</span> -->
                    </p>
                </div>
                <?php if (!empty($groupedNotifications)): ?>
                    <?php foreach ($groupedNotifications as $dateLabel => $items): ?>
                        <div class="notifications-date-group">
                            <div class="px-3 pt-3 pb-1 text-secondary small fw-semibold">
                                <?= htmlspecialchars($dateLabel) ?>
                            </div>
                            <div class="card-body p-3 pt-2">
                                <div class="timeline timeline-one-side">
                                    <?php foreach ($items as $note): ?>
                                        <div class="timeline-block mb-3">
                                            <span class="timeline-step">
                                                <i class="<?= htmlspecialchars($note['icon']) ?> <?= htmlspecialchars($note['color'] ?? '') ?> text-gradient"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <p class="text-dark text-sm fw-semibold mb-0 text-truncate flex-grow-1 notif-title">
                                                        <?= htmlspecialchars($note['title']) ?>
                                                    </p>
                                                    <span class="text-secondary text-xs text-nowrap ms-2">
                                                        <?= date('M j, Y g:i A', strtotime($note['created_at'])); ?>
                                                    </span>
                                                </div>
                                                <?php if (!empty($note['message'])): ?>
                                                    <div class="text-xs mt-1 text-muted text-break notif-message">
                                                        <?= htmlspecialchars($note['message']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-secondary py-4">No notifications found.</div>
                <?php endif; ?>
            </div>
        </div>
        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamerscodes 2025. All rights reserved.
    </footer>

    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>

<style>
    /* Responsive text tweaks for notifications page */
    @media (max-width: 575.98px) {
        .notif-title {
            font-size: 0.95rem;
        }

        .notif-message {
            display: -webkit-box;
            line-clamp: 2;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    }
</style>