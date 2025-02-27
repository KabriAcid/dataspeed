<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            width: 450px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .header {
            background: #d39b9b;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
            color: white;
        }
        .content {
            text-align: left;
            padding: 15px;
        }
        .box {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .bold {
            font-weight: bold;
        }
        .btn {
            display: block;
            width: 100%;
            background: #8b3c3c;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Review Payment</div>
        <p>Please review the details below</p>
        <div class="box">
            <p class="bold">BUYPOWER</p>
            <p>Electricity/Power</p>
        </div>
        <div class="content">
            <p><span class="bold">Payment Source:</span> 2100111345</p>
            <p><span class="bold">Bill Type:</span> Electricity</p>
            <p><span class="bold">Bill Number:</span> NN00111345</p>
            <p><span class="bold">Transaction Number:</span> EUE0111345</p>
            <p><span class="bold">Amount:</span> N20,000.00</p>
            <p><span class="bold">Biller:</span> 2100111345</p>
        </div>
        <button class="btn">Confirm</button>
    </div>
</body>
</html>
