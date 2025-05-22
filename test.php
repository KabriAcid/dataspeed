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
            <h2>Welcome Back</h2>
            <p class="subtext"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="text-alertGreen" style="font-size: 12px;">
                    <path fill-rule="evenodd"
                        d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"
                        clip-rule="evenodd"></path>
                </svg> Enter your PIN</p>

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
                <button onclick="backspace()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="currentColor" class="w-25 h-25 fs-2">
                        <path fill-rule="evenodd"
                            d="M2.515 10.674a1.875 1.875 0 000 2.652L8.89 19.7c.352.351.829.549 1.326.549H19.5a3 3 0 003-3V6.75a3 3 0 00-3-3h-9.284c-.497 0-.974.198-1.326.55l-6.375 6.374zM12.53 9.22a.75.75 0 10-1.06 1.06L13.19 12l-1.72 1.72a.75.75 0 101.06 1.06l1.72-1.72 1.72 1.72a.75.75 0 101.06-1.06L15.31 12l1.72-1.72a.75.75 0 10-1.06-1.06l-1.72 1.72-1.72-1.72z"
                            clip-rule="evenodd"></path>
                    </svg></button>
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