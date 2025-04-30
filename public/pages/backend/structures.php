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
            <h5 class="text-center fw-bold">Structures</h5>
        </header>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = new mysqli('localhost', 'root', '', 'dataspee_db');
            $name = $_POST['name'];
            $sql = "SELECT first_name from users where first_name LIKE '%$name%'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            echo $row['first_name'];
        }
        ?>

        <div class="container-fluid">
            <form action="" method="post">
                <input type="text" name="name" placeholder="Name" id="" class="form-control">
                <input type="submit" value="Submit" class="btn-success btn mt-3">
            </form>
        </div>



        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>
    <footer class="my-4">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>