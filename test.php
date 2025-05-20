<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PIN Pad Modal</title>
    <link rel="stylesheet" href="style.css" />
    <style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
    }

    button {
        cursor: pointer;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        width: 300px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .subtext {
        color: green;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .pin-dots {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 10px 0 20px;
    }

    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #ccc;
    }

    .filled {
        background-color: green;
    }

    .keypad {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .keypad button {
        font-size: 18px;
        padding: 15px;
        border: none;
        background-color: #f0f0f0;
        border-radius: 50%;
        transition: background 0.3s;
    }

    .keypad button:hover {
        background-color: #ddd;
    }

    .modal-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .modal-actions button {
        background: none;
        border: none;
        color: #000;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <button onclick="openModal()">Open PIN Pad</button>

    <div id="pinModal" class="modal">
        <div class="modal-content">
            <img src="https://via.placeholder.com/80" alt="Avatar" class="avatar" />
            <h2>Welcome Back</h2>
            <p class="subtext">🔒 Enter your PIN</p>

            <div class="pin-dots">
                <span class="dot" id="dot1"></span>
                <span class="dot" id="dot2"></span>
                <span class="dot" id="dot3"></span>
                <span class="dot" id="dot4"></span>
            </div>

            <div class="keypad">
                <button onclick="press(1)">1</button>
                <button onclick="press(2)">2</button>
                <button onclick="press(3)">3</button>
                <button onclick="press(4)">4</button>
                <button onclick="press(5)">5</button>
                <button onclick="press(6)">6</button>
                <button onclick="press(7)">7</button>
                <button onclick="press(8)">8</button>
                <button onclick="press(9)">9</button>
                <button onclick="resetPin()">⟲</button>
                <button onclick="press(0)">0</button>
                <button onclick="backspace()">←</button>
            </div>

            <div class="modal-actions">
                <button onclick="closeModal()">Logout</button>
                <button onclick="forgotPin()">Forgot PIN?</button>
            </div>
        </div>
    </div>

    <script>
    let pin = "";

    function openModal() {
        document.getElementById("pinModal").style.display = "flex";
        resetPin();
    }

    function closeModal() {
        document.getElementById("pinModal").style.display = "none";
        resetPin();
    }

    function forgotPin() {
        alert("Redirect to PIN recovery flow...");
    }

    function press(num) {
        if (pin.length < 4) {
            pin += num;
            updateDots();
            if (pin.length === 4) {
                setTimeout(() => {
                    alert("PIN entered: " + pin);
                    closeModal();
                }, 200);
            }
        }
    }

    function backspace() {
        pin = pin.slice(0, -1);
        updateDots();
    }

    function resetPin() {
        pin = "";
        updateDots();
    }

    function updateDots() {
        for (let i = 1; i <= 4; i++) {
            document.getElementById("dot" + i).classList.remove("filled");
        }
        for (let i = 1; i <= pin.length; i++) {
            document.getElementById("dot" + i).classList.add("filled");
        }
    }
    </script>
</body>

</html>