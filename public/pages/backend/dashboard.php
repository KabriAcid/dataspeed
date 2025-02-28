<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . "/../../partials/header.php";
?>

<body style="font-family: 'Montserrat';">
    <?php require __DIR__ . "/../../partials/sidebar.php"; ?>
    <main>
        
        <?php require __DIR__ . "/../../partials/bottom-nav.php"; ?>
    </main>
</body>

</html>