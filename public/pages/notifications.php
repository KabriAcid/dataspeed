<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';

$notifications = getUserNotifications($pdo, $user_id);
$groupedNotifications = groupNotificationsByDate($notifications);

?>

<body>
    <main class="container-fluid py-4">
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
            <div class="card h-100 p-0">
                <div class="card-header pb-0">
                    <h6>Orders overview</h6>
                    <i class="fa fa-home"></i>
                    <p class="text-sm">
                        <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                        <span class="font-weight-bold">24%</span> this month
                    </p>
                </div>
                <div class="notifications-date-group">
                    <div class="notifications-date-header">
                        <i class="fa fa-clock clock-icon"></i>
                        <span><?= $date ?? '' ?></span>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline timeline-one-side">
                            <?php if (!empty($notifications)): ?>
                                <?php foreach ($notifications as $note): ?>
                                    <div class="timeline-block mb-3">
                                        <span class="timeline-step">
                                            <i class="ni <?= htmlspecialchars($note['icon']) ?> <?= htmlspecialchars($note['icon_color'] ?? '') ?> text-gradient"></i>
                                        </span>
                                        <div class="timeline-content">
                                            <h6 class="text-dark text-sm font-weight-bold mb-0">
                                                <?= htmlspecialchars($note['title']) ?>
                                            </h6>
                                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                                <?= date('d M g:i A', strtotime($note['created_at'])) ?>
                                            </p>
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

    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>