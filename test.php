<?php
require __DIR__ . '/config/config.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Financial Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="">

    <main>
        <div class="container">
            <button id="airtimeButton" class="action-button bg-success">
                Airtime
            </button>
        </div>
    </main>

    <script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>
    <script>
        document.getElementById('airtimeButton').addEventListener('click', function() {
            MonnifySDK.initialize({
                amount: 1000,
                currency: "NGN",
                reference: "txn-" + Math.floor((Math.random() * 1000000000) + 1),
                customerName: "Kabri Acid",
                customerEmail: "kabriacid01@gmail.com",
                apiKey: "<?php echo $_ENV['MONNIFY_API_KEY']; ?>",
                contractCode: "<?php echo $_ENV['MONNIFY_CONTRACT_CODE']; ?>",
                paymentDescription: "Airtime Purchase",
                metadata: {
                    customField: "Custom data"
                },
                onComplete: function(response) {
                    console.log("Payment successful: ", response);
                },
                onClose: function(data) {
                    console.log("Payment closed: ", data);
                }
            });
        });
    </script>
</body>

</html>