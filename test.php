<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Financial Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="">
    <?php
    // Load environment variables
    require __DIR__ . '/config/config.php';

    // Flutterwave API endpoint for initiating payments
    $url = "https://api.flutterwave.com/v3/payments";

    // Set up the payload for the payment request
    $data = [
        "tx_ref" => "TX_" . time(),
        "amount" => 1000, // Payment amount
        "currency" => "NGN",
        "redirect_url" => "https://yourwebsite.com/payment-callback.php",
        "payment_options" => "card,banktransfer,ussd",
        "customer" => [
            "email" => "kabriacid01@gmail.com",
            "phonenumber" => "08012345678",
            "name" => "Abdullahi Kabri"
        ],
        "customizations" => [
            "title" => "DataSpeed Payment",
            "description" => "Payment for data recharge",
            "logo" => "https://yourwebsite.com/logo.png"
        ]
    ];

    // Initialize cURL session
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $_ENV['FLW_SECRET_KEY'],
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute request and capture the response
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response
    $result = json_decode($response, true);

    // Handle the response
    if ($result && $result['status'] === 'success') {
        $payment_link = $result['data']['link'];
        header('Location: ' . $payment_link); // Redirect user to the payment page
        exit();
    } else {
        echo "Payment initiation failed: " . $result['message'];
    }
    ?>

</body>

</html>