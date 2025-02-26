<?php
require __DIR__ . '/../../partials/header.php'; 
require __DIR__ . '/../../../config/config.php'; // Ensure database connection is included
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Bill</title>
    <link rel="stylesheet" href="style.css">
    <style>
        

body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f7f7f7;
}

 

header {
    text-align: center;
    background: #dba7a3;
    padding: 15px;
    border-radius: 10px;
    font-size: 24px;
    font-weight: bold;
    color: white;
}

.buttons {
    display: flex;
    justify-content: space-between;
    margin: 20px 0;
}

.bill-btn {
    width: 48%;
    padding: 10px;
    background: #f4d3d1;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.bill-btn:hover {
    background: #dba7a3;
    color: white;
}

.recent-payments {
    margin-top: 20px;
}

h2 {
    margin-bottom: 10px;
}

.search-bar input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.payments-list {
    margin-top: 15px;
}

.payment-item {
    display: flex;
    align-items: center;
    background: #fff3f2;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.icon {
    background: #dba7a3;
    padding: 10px;
    border-radius: 5px;
    color: white;
    font-size: 20px;
    margin-right: 10px;
}

.details {
    flex-grow: 1;
}

.title {
    font-weight: bold;
}

.amount {
    font-weight: bold;
    color: black;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        width: 90%;
    }

    .buttons {
        flex-direction: column;
    }

    .bill-btn {
        width: 100%;
        margin-bottom: 10px;
    }
}

    </style>
</head>
<body>

    <div class="container">
        <header>
            <h1>Pay Bill</h1>
        </header>

        <section class="buttons">
            <button class="bill-btn">📄 Pay a new bill</button>
            <button class="bill-btn">💾 Pay saved bill</button>
        </section>

        <section class="recent-payments">
            <h2>Recent Payments</h2>
            <div class="search-bar">
                <input type="text" placeholder="🔍 Search">
            </div>

            <div class="payments-list">
                <?php foreach ($payments as $payment) : ?>
                    <div class="payment-item">
                        <div class="icon">📋</div>
                        <div class="details">
                            <p class="title"><?php echo $payment['title']; ?></p>
                            <p class="desc"><?php echo $payment['description']; ?></p>
                        </div>
                        <p class="amount">₦<?php echo number_format($payment['amount'], 0); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

</body>
</html>
