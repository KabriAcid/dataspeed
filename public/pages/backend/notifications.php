<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';

?>

<body>
    <main class="container py-4">
        <header class="mb-4 text-center">
            <h5 class="fw-bold">Notifications</h5>
        </header>
        <!-- Notifications List -->
        <?php
        $notifications = getUserNotifications($pdo, $user_id);
        $groupedNotifications = groupNotificationsByDate($notifications);
        ?>

        <div class="notifications">
            <?php foreach ($groupedNotifications as $date => $group): ?>
            <div class="date-group">
                <div class="date-header">
                    <i class="fa fa-clock clock-icon"></i>
                    <span><?= htmlspecialchars($date) ?></span>
                </div>

                <?php foreach ($group as $note): ?>
                <div class="notification-card <?= $note['is_read'] === '0' ? 'unread' : '' ?>">
                    <div class="notification-content">
                        <div class="icon-wrapper <?= htmlspecialchars($note['type']) ?>">
                            <i class="fa <?= htmlspecialchars($note['icon']) ?>"></i>
                        </div>
                        <div class="notification-details">
                            <div class="notification-header">
                                <h2><?= htmlspecialchars($note['title']) ?></h2>
                                <span class="time"><?= (new DateTime($note['created_at']))->format('g:i A') ?></span>
                            </div>
                            <p><?= htmlspecialchars($note['message']) ?>&period;</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>


        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamcodes 2025. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>