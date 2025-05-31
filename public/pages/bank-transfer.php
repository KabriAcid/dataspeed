<?php
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../../functions/getVirtualAccount.php';

// Assume user is logged in and $userId is set
$userId = $_SESSION['user']['user_id'] ?? null;

$virtualAccount = getUserVirtualAccount($pdo, $userId);
?>

<body>
    <main class="container py-4">
        <h2>Your Virtual Account Details</h2>

        <?php if ($virtualAccount): ?>
            <p><strong>Bank Name:</strong> <?= htmlspecialchars($virtualAccount['bank_name']) ?></p>
            <p><strong>Account Number:</strong> <?= htmlspecialchars($virtualAccount['account_number']) ?></p>
        <?php else: ?>
            <p>No virtual account found. Please generate one.</p>
        <?php endif; ?>
    </main>
</body>

</html>