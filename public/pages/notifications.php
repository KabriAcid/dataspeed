<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';

?>

<body class="pt-5">
    <main class="container-fluid py-4">
        <header class="page-header mb-4 text-center">
            <svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h5 class="fw-bold">Notifications</h5>
            <span></span>
        </header>
        <!-- Notifications List -->
        <?php
        $notifications = getUserNotifications($pdo, $user_id);
        $groupedNotifications = groupNotificationsByDate($notifications);
        ?>

        <div class="notifications">
            <?php if($groupedNotifications){
            foreach ($groupedNotifications as $date => $group): ?>
                <div class="date-group">
                    <div class="date-header">
                        <i class="fa fa-clock clock-icon"></i>
                        <span><?= htmlspecialchars($date) ?></span>
                    </div>

                    <?php foreach ($group as $note): ?>
                    <div class="bg-white border-0 rounded shadow-xl p-3 my-4 animate-fade-in cursor-pointer <?= $note['is_read'] === '0' ? 'unread' : '' ?>">
                        <div class="notification-content">
                            <div class="icon-wrapper <?= htmlspecialchars($note['type']) ?>">
                                <i class="fa <?= htmlspecialchars($note['icon']) ?>"></i>
                            </div>
                            <div class="notification-details">
                                <div class="notification-header">
                                    <h2><?= htmlspecialchars($note['title']) ?></h2>
                                    <span class="notification-time"><?= (new DateTime($note['created_at']))->format('g:i A') ?></span>
                                </div>
                                <p><?= htmlspecialchars($note['message']) ?>&period;</p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; 
            } else { ?>
                <p class="text-center text-secondary">No notifications found.</p>
                <?php
            }
            ?>
        </div>

        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamcodes 2025. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>