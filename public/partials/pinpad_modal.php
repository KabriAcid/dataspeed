<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure PIN Pad</title>
    <link rel="stylesheet" href="../assets/css/creative-tim.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #0066cc;
            --color-primary-light: #0080ff;
            --color-primary-dark: #004d99;
            --color-secondary: #6c757d;
            --color-accent: #28a745;
            --color-neutral-50: #f8f9fa;
            --color-neutral-100: #f1f3f5;
            --color-neutral-200: #e9ecef;
            --color-neutral-300: #dee2e6;
            --color-neutral-400: #ced4da;
            --color-neutral-500: #adb5bd;
            --color-neutral-600: #6c757d;
            --color-neutral-700: #495057;
            --color-neutral-800: #343a40;
            --color-neutral-900: #212529;
            --space-1: 0.5rem;
            --space-2: 1rem;
            --space-3: 1.5rem;
            --space-4: 2rem;
            --transition-fast: 150ms;
            --transition-normal: 250ms;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
        }
  
          
    </style>
</head>
<body>
  <div class="pin-container">
    <div class="icon-section">
      <div class="icon mb-3">
        <img src="../assets/img/avatar.jpg" class="avatar avatar-md" alt="">
      </div>
    </div>

    <h2 class="welcome-text">Welcome Back</h2>

    <div class="pin-section">
      <p class="pin-instruction"><span class="check-icon">✓</span> Enter your PIN</p>
      <div class="pin-dots">
        <div class="pin-dot" id="dot-1"></div>
        <div class="pin-dot" id="dot-2"></div>
        <div class="pin-dot" id="dot-3"></div>
        <div class="pin-dot" id="dot-4"></div>
      </div>
    </div>

    <div class="keypad">
      <div class="keypad-row">
        <button class="key-button" data-value="1">1</button>
        <button class="key-button" data-value="2">2</button>
        <button class="key-button" data-value="3">3</button>
      </div>
      <div class="keypad-row">
        <button class="key-button" data-value="4">4</button>
        <button class="key-button" data-value="5">5</button>
        <button class="key-button" data-value="6">6</button>
      </div>
      <div class="keypad-row">
        <button class="key-button" data-value="7">7</button>
        <button class="key-button" data-value="8">8</button>
        <button class="key-button" data-value="9">9</button>
      </div>
      <div class="keypad-row">
        <div class="key-spacer"></div>
        <button class="key-button" data-value="0">0</button>
        <button class="key-backspace" id="backspace">⌫</button>
      </div>
    </div>

    <div class="footer-actions">
      <button class="action-button logout">↩ Logout</button>
      <button class="action-button forgot shadow-sm">? Forgot PIN?</button>
    </div>
  </div>

  <script>
    let pin = '';
    const maxLength = 4;

    const dotEls = [
      document.getElementById('dot-1'),
      document.getElementById('dot-2'),
      document.getElementById('dot-3'),
      document.getElementById('dot-4')
    ];

    function updateDots() {
      dotEls.forEach((dot, i) => {
        if (i < pin.length) {
          dot.classList.add('filled');
        } else {
          dot.classList.remove('filled');
        }
      });
    }

    function addDigit(digit) {
      if (pin.length < maxLength) {
        pin += digit;
        updateDots();
        if (pin.length === maxLength) {
          setTimeout(() => {
            console.log('PIN entered:', pin);
            alert('PIN entered: ' + pin);
            resetPin();
          }, 300);
        }
      }
    }

    function removeDigit() {
      if (pin.length > 0) {
        pin = pin.slice(0, -1);
        updateDots();
      }
    }

    function resetPin() {
      pin = '';
      updateDots();
    }

    document.querySelectorAll('.key-button').forEach(button => {
      button.addEventListener('click', () => {
        const value = button.getAttribute('data-value');
        addDigit(value);
      });
    });

    document.getElementById('backspace').addEventListener('click', () => {
      removeDigit();
    });

    document.addEventListener('keydown', e => {
      if (/^[0-9]$/.test(e.key)) {
        addDigit(e.key);
      } else if (e.key === 'Backspace') {
        removeDigit();
      }
    });

    document.querySelector('.logout').addEventListener('click', () => {
      alert('Logging out...');
    });

    document.querySelector('.forgot').addEventListener('click', () => {
      alert('Forgot PIN clicked');
    });
  </script>
</body>

</html>