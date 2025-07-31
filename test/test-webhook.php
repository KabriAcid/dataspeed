<head>
     <!-- water.css cdn-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/water.css/2.0.0/water.min.css">
 </head>
<?php
echo "<h2>Webhook Testing</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Test different webhook scenarios
    $testData = json_decode($_POST['webhook_data'], true);

    echo "<h3>Sending Webhook:</h3>";
    echo "<pre>" . json_encode($testData, JSON_PRETTY_PRINT) . "</pre>";

    // Send to your webhook endpoint
    $webhookUrl = "http://localhost/dataspeed/public/webhooks/billstack-deposit.php";

    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'User-Agent: Test-Webhook/1.0'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "<h3>Webhook Response:</h3>";
    echo "<p><strong>HTTP Code:</strong> $httpCode</p>";
    echo "<p><strong>Response:</strong></p>";
    echo "<pre>$response</pre>";

    // Show log file content
    $logFile = __DIR__ . '/public/webhooks/deposit-log.txt';
    if (file_exists($logFile)) {
        echo "<h3>Log File Content:</h3>";
        echo "<pre>" . htmlspecialchars(file_get_contents($logFile)) . "</pre>";
    }
}
?>

<form method="POST">
    <h3>Test Webhook Scenarios:</h3>

    <h4>Successful Deposit:</h4>
    <textarea name="webhook_data" rows="8" cols="60">{
    "status": "successful",
    "account_number": "1234567890",
    "amount": 1000,
    "reference": "TEST_<?= time() ?>",
    "sender_name": "Test User",
    "transaction_date": "<?= date('Y-m-d H:i:s') ?>"
}</textarea>
    <br><br>
    <button type="submit">Send Webhook</button>
</form>

<hr>

<h4>Quick Test Buttons:</h4>
<button onclick="sendQuickWebhook(500)">Test ₦500 Deposit</button>
<button onclick="sendQuickWebhook(1000)">Test ₦1000 Deposit</button>
<button onclick="sendFailedWebhook()">Test Failed Transaction</button>

<script>
    function sendQuickWebhook(amount) {
        const data = {
            "status": "successful",
            "account_number": "1234567890",
            "amount": amount,
            "reference": "QUICK_TEST_" + Date.now(),
            "sender_name": "Quick Test",
            "transaction_date": new Date().toISOString()
        };

        document.querySelector('textarea[name="webhook_data"]').value = JSON.stringify(data, null, 2);
        document.querySelector('form').submit();
    }

    function sendFailedWebhook() {
        const data = {
            "status": "failed",
            "account_number": "1234567890",
            "amount": 500,
            "reference": "FAILED_TEST_" + Date.now(),
            "sender_name": "Failed Test",
            "transaction_date": new Date().toISOString()
        };

        document.querySelector('textarea[name="webhook_data"]').value = JSON.stringify(data, null, 2);
        document.querySelector('form').submit();
    }
</script>