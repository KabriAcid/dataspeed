<?php
// filepath: c:\xampp\htdocs\dataspeed\test-balance-update.php
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../functions/Model.php';
require __DIR__ . '/../functions/utilities.php';

session_start();

// Set test user ID (use a real user ID from your database)
$test_user_id = 1; // Change this to an actual user ID
$_SESSION['user_id'] = $test_user_id;

echo "<h2>Balance Update Testing</h2>";

// Get current balance
$current_balance = getUserBalance($pdo, $test_user_id);
echo "<p><strong>Current Balance:</strong> ₦" . number_format($current_balance, 2) . "</p>";

// Test 1: Add money to balance
if (isset($_GET['add'])) {
    $amount = floatval($_GET['add']);

    try {
        $stmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = wallet_balance + ? WHERE user_id = ?");
        $result = $stmt->execute([$amount, $test_user_id]);

        if ($result) {
            echo "<div style='color: green; padding: 10px; background: #f0f8f0; margin: 10px 0;'>";
            echo "✅ Successfully added ₦" . number_format($amount, 2) . " to balance!";
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div style='color: red; padding: 10px; background: #fff0f0; margin: 10px 0;'>";
        echo "❌ Error: " . $e->getMessage();
        echo "</div>";
    }
}

// Test 2: Simulate webhook deposit
if (isset($_GET['webhook'])) {
    $amount = floatval($_GET['webhook']);

    // Simulate a webhook call
    $webhookData = [
        'status' => 'successful',
        'account_number' => '1234567890', // Use a real virtual account
        'amount' => $amount,
        'reference' => 'TEST_' . time(),
        'sender_name' => 'Test Deposit'
    ];

    echo "<div style='padding: 10px; background: #f0f0f8; margin: 10px 0;'>";
    echo "<strong>Simulating Webhook:</strong><br>";
    echo "<pre>" . json_encode($webhookData, JSON_PRETTY_PRINT) . "</pre>";
    echo "</div>";
}

// Get updated balance
$new_balance = getUserBalance($pdo, $test_user_id);
echo "<p><strong>Updated Balance:</strong> ₦" . number_format($new_balance, 2) . "</p>";

?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    .test-btn {
        display: inline-block;
        padding: 10px 15px;
        margin: 5px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .test-btn:hover {
        background: #0056b3;
    }
</style>

<h3>Test Actions:</h3>
<a href="?add=100" class="test-btn">Add ₦100</a>
<a href="?add=500" class="test-btn">Add ₦500</a>
<a href="?add=1000" class="test-btn">Add ₦1000</a>
<a href="?webhook=250" class="test-btn">Simulate ₦250 Webhook</a>
<a href="test-balance.php" class="test-btn">Refresh Page</a>

<h3>Test AJAX Endpoint:</h3>
<button onclick="testAjax()" class="test-btn">Test AJAX Call</button>
<div id="ajax-result" style="margin-top: 10px; padding: 10px; background: #f8f9fa;"></div>

<script>
    function testAjax() {
        fetch('../public/pages/update-balance.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('ajax-result').innerHTML =
                    '<strong>AJAX Response:</strong><br><pre>' +
                    JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                document.getElementById('ajax-result').innerHTML =
                    '<strong style="color: red;">AJAX Error:</strong><br>' + error;
            });
    }
</script>