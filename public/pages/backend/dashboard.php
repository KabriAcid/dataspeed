<?php
session_start();
// if (!isset($_SESSION['user'])) {
//     header('Location: login.php');
//     exit;
// }
require __DIR__ . "/../../partials/header.php";
?>

<body style="font-family: 'Montserrat';">
    <?php require __DIR__ . "/../../partials/sidebar.php"; ?>
    <main>
        <?php
            if(isset($_SESSION['user'])){
                foreach($_SESSION['user'] as $key => $value){
                    printf("%s: %s ", $key, $value);
                    echo "<br>";
                }
            }
        ?>
        <a href="logout.php">Logout</a>
    </main>
</body>

</html>