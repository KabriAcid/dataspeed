<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . "/../../partials/header.php";
?>

<body style="font-family: 'Montserrat';">
    <?php require __DIR__ . "/../../partials/navbar.php"; ?>
    <main class="container mt-3">
        <!-- Header content -->
        <div class="header-container">
            <div class="left-content">
                <p class="mb-1">Total balance</p>
                <h3 class="font-weight-bold">&#8358;85,055.99</h3>
            </div>
            <div class="right-content">
                <img src="../../assets/img/avatar.jpg" alt="" class="avatar">
            </div>
        </div>
        <!-- Main content -->
        <div class="container">
            <h1>Welcome to Your Dashboard</h1>
            <div class="card">
                <a href="buy_airtime.php">
                    <h2>Buy Airtime</h2>
                    <p>Purchase airtime for your mobile phone.</p>
                </a>
            </div>
            <div class="card">
                <a href="buy_data.php">
                    <h2>Buy Data</h2>
                    <p>Purchase data bundles for internet usage.</p>
                </a>
            </div>
            <div class="card">
                <a href="pay_bills.php">
                    <h2>Pay Bills</h2>
                    <p>Pay your utility and other bills.</p>
                </a>
            </div>
            <div class="card">
                <a href="transaction_history.php">
                    <h2>Transaction History</h2>
                    <p>View your past transactions.</p>
                </a>
            </div>
            <div class="card">
                <a href="logout.php">
                    <h2>Logout</h2>
                    <p>Sign out of your account.</p>
                </a>
            </div>
        </div>

    </main>
</body>

</html>