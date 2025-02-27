<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 400px;
        }
        .fingerprint {
            width: 80px;
            height: 80px;
            margin: 10px auto;
            background: url('fingerprint.png') no-repeat center/cover;
            cursor: pointer;
        }
        .pin-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 10px 0;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: gray;
        }
        .keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 200px;
            margin: 20px auto;
        }
        .key {
            padding: 15px;
            font-size: 20px;
            background: #ddd;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .key:active {
            background: #bbb;
        }
        @media (max-width: 600px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verification</h2>
        <div class="fingerprint" onclick="authenticateFingerprint()"></div>
        <p>Enter your PIN</p>
        <div class="pin-dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
        <div class="keypad">
            <button class="key" onclick="enterPin(1)">1</button>
            <button class="key" onclick="enterPin(2)">2</button>
            <button class="key" onclick="enterPin(3)">3</button>
            <button class="key" onclick="enterPin(4)">4</button>
            <button class="key" onclick="enterPin(5)">5</button>
            <button class="key" onclick="enterPin(6)">6</button>
            <button class="key" onclick="enterPin(7)">7</button>
            <button class="key" onclick="enterPin(8)">8</button>
            <button class="key" onclick="enterPin(9)">9</button>
            <button class="key" onclick="enterPin(0)" style="grid-column: span 3;">0</button>
        </div>
    </div>
    <script>
        let pin = "";
        function enterPin(num) {
            if (pin.length < 4) {
                pin += num;
                updateDots();
            }
            if (pin.length === 4) {
                setTimeout(() => alert("PIN entered: " + pin), 500);
            }
        }
        function updateDots() {
            document.querySelectorAll(".dot").forEach((dot, index) => {
                dot.style.background = index < pin.length ? "black" : "gray";
            });
        }
        function authenticateFingerprint() {
            alert("Fingerprint authentication triggered");
        }
    </script>
</body>
</html>
