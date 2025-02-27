<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Bill</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background: #f9f4f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 800px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            gap: 10px;
        }
        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background: #d89ca0;
            color: white;
            transition: 0.3s;
        }
        .btn:hover {
            background: #b5767b;
        }
        .search-box {
            margin: 20px 0;
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        input {
            flex: 1;
            border: none;
            outline: none;
        }
        .recent-payments {
            margin-top: 20px;
        }
        .payment-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffe8e8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        /* Responsive Design */
        @media (max-width: 600px) {
            .buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pay Bill</h2>
        <div class="buttons">
            <button class="btn">Pay a New Bill</button>
            <button class="btn">Pay Saved Bill</button>
        </div>
        <div class="search-box">
            <span>&#128269;</span>
            <input type="text" placeholder="Search recent payments...">
        </div>
        <div class="recent-payments">
            <h3>Recent Payments</h3>
            <div class="payment-card">
                <span>BUYOPOWER</span>
                <strong>N24,000</strong>
            </div>
            <div class="payment-card">
                <span>DSTV</span>
                <strong>N24,000</strong>
            </div>
        </div>
    </div>
</body>
</html>
