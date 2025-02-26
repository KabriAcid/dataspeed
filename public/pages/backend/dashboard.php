<?php 
require __DIR__ . '/../../partials/header.php'; 
require __DIR__ . '/../../../config/config.php'; // Ensure database connection is included
session_start();

// Check if user session exists
if (isset($_SESSION['user']) && isset($_SESSION['user']['first_name'])) {
    $username = $_SESSION['user']['first_name'];
} else {
    $username = "Unknown User"; // Fallback if session is not set
}
?>

<body style="font-family: 'Montserrat';">
   
    
    <main class="container">
        <header>
            <h2>Welcome, <strong><?php echo htmlspecialchars($username); ?>.</strong></h2>
            <img src="profile.jpg" alt="User Profile" class="profile-pic">
        </header>
        <section class="balance-card">
            <h3>Total Balance</h3>
            <p class="balance">₦0.00</p>
            <button class="currency-btn">NGN</button>
            <div class="actions">
                <button>Bills</button>
                <button>Airtime</button>
                <button>Repayment</button>
                <button>More</button>
            </div>
        </section>
        <section class="transactions">
            <h3>Recent Transactions</h3>
            <ul>
                <li><span>Airtime</span> <span>₦20,000.34</span></li>
                <li><span>Loan Repayment</span> <span>₦20,000.34</span></li>
                <li><span>EKEDC bill</span> <span>₦20,000.34</span></li>
                <li><span>DSTV</span> <span>₦20,000.34</span></li>
                <li><span>Deep Freezer</span> <span>₦20,000.34</span></li>
            </ul>
        </section>
        <a href="logout.php">Logout</a>
    </main>
    
    <script src="assets/js/auth.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
