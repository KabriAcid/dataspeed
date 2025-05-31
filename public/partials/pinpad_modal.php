<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure PIN Pad</title>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--color-neutral-50);
            -webkit-font-smoothing: antialiased;
        }

        .pin-container {
            width: 100%;
            max-width: 360px;
            padding: var(--space-4);
        }

        .logo-section {
            text-align: center;
            margin-bottom: var(--space-3);
        }

        .logo {
            width: 64px;
            height: 64px;
            margin: 0 auto var(--space-2);
            background: var(--color-primary);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }

        .logo-circle {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-full);
            border: 4px solid white;
            background: linear-gradient(135deg, var(--color-primary-light), var(--color-primary-dark));
        }

        .company-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--color-neutral-800);
        }

        .welcome-text {
            font-size: 1.75rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: var(--space-4);
            color: var(--color-neutral-900);
        }

        .pin-section {
            text-align: center;
            margin-bottom: var(--space-4);
        }

        .pin-instruction {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-1);
            font-size: 0.875rem;
            margin-bottom: var(--space-2);
            color: var(--color-neutral-700);
        }

        .check-icon {
            color: var(--color-accent);
        }

        .pin-dots {
            display: flex;
            gap: var(--space-2);
            justify-content: center;
            margin-top: var(--space-1);
        }

        .pin-dot {
            width: 16px;
            height: 16px;
            border-radius: var(--radius-full);
            border: 2px solid var(--color-neutral-400);
            transition: all var(--transition-normal);
        }

        .pin-dot.filled {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            transform: scale(1.1);
        }

        .keypad {
            margin-bottom: var(--space-4);
        }

        .keypad-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--space-3);
        }

        .key-button,
        .key-backspace {
            width: 64px;
            height: 64px;
            border: none;
            background: transparent;
            border-radius: var(--radius-full);
            background-color: white;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--color-neutral-800);
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-fast);
        }

        .key-button:hover,
        .key-backspace:hover {
            background-color: var(--color-neutral-100);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .key-button:active,
        .key-backspace:active {
            transform: translateY(0);
            background-color: var(--color-neutral-200);
        }

        .key-spacer {
            width: 64px;
        }

        .key-backspace {
            opacity: 0;
            visibility: hidden;
            transition: opacity var(--transition-normal),
                        visibility var(--transition-normal),
                        transform var(--transition-fast),
                        background-color var(--transition-fast);
        }

        .key-backspace.visible {
            opacity: 1;
            visibility: visible;
        }

        .footer-actions {
            display: flex;
            justify-content: space-between;
            padding: 0 var(--space-2);
        }

        .action-button {
            border: none;
            background: none;
            color: var(--color-neutral-600);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: color var(--transition-fast);
            display: flex;
            align-items: center;
            gap: var(--space-1);
        }

        .action-button:hover {
            color: var(--color-primary);
        }

        @keyframes pinPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .pin-feedback {
            animation: pinPulse 0.3s ease-in-out;
        }

        @media (max-width: 380px) {
            .key-button,
            .key-backspace,
            .key-spacer {
                width: 56px;
                height: 56px;
                font-size: 1.5rem;
            }

            .keypad-row {
                margin-bottom: var(--space-2);
            }
        }
    </style>
</head>
<body>
    <div class="pin-container">
        <div class="logo-section">
            <div class="logo">
                <div class="logo-circle"></div>
            </div>
            <h1 class="company-name">Dataspeed</h1>
        </div>

        <h2 class="welcome-text">Welcome Back</h2>

        <div class="pin-section">
            <p class="pin-instruction">
                <span class="check-icon">✓</span> Enter your PIN
            </p>
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
                <button class="key-backspace" id="backspace">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7"><path fill-rule="evenodd" d="M2.515 10.674a1.875 1.875 0 000 2.652L8.89 19.7c.352.351.829.549 1.326.549H19.5a3 3 0 003-3V6.75a3 3 0 00-3-3h-9.284c-.497 0-.974.198-1.326.55l-6.375 6.374zM12.53 9.22a.75.75 0 10-1.06 1.06L13.19 12l-1.72 1.72a.75.75 0 101.06 1.06l1.72-1.72 1.72 1.72a.75.75 0 101.06-1.06L15.31 12l1.72-1.72a.75.75 0 10-1.06-1.06l-1.72 1.72-1.72-1.72z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
        </div>

        <div class="footer-actions">
            <button class="action-button logout">
                ↩ Logout
            </button>
            <button class="action-button forgot">
                ? Forgot PIN?
            </button>
        </div>
    </div>

    <script>
        class PinPad {
            constructor(options = {}) {
                this.pin = '';
                this.maxLength = options.maxLength || 4;
                this.onComplete = options.onComplete || (() => {});
                
                this.dots = Array.from(document.querySelectorAll('.pin-dot'));
                this.keyButtons = document.querySelectorAll('.key-button');
                this.backspaceButton = document.getElementById('backspace');
                
                this.init();
            }
            
            init() {
                this.keyButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const value = button.getAttribute('data-value');
                        this.addDigit(value);
                    });
                });
                
                this.backspaceButton.addEventListener('click', () => {
                    this.removeDigit();
                });
                
                document.addEventListener('keydown', (e) => {
                    if (/^[0-9]$/.test(e.key)) {
                        this.addDigit(e.key);
                    } else if (e.key === 'Backspace') {
                        this.removeDigit();
                    }
                });

                // Initialize other buttons
                document.querySelector('.logout').addEventListener('click', () => {
                    alert('Logging out...');
                });
                
                document.querySelector('.forgot').addEventListener('click', () => {
                    alert('PIN reset functionality would be triggered here.');
                });
            }
            
            addDigit(digit) {
                if (this.pin.length < this.maxLength) {
                    this.pin += digit;
                    this.updateDots();
                    this.animateButton(document.querySelector(`[data-value="${digit}"]`));
                    this.toggleBackspaceButton();
                    
                    if (this.pin.length === this.maxLength) {
                        setTimeout(() => {
                            this.onComplete(this.pin);
                        }, 300);
                    }
                }
            }
            
            removeDigit() {
                if (this.pin.length > 0) {
                    this.pin = this.pin.slice(0, -1);
                    this.updateDots();
                    this.toggleBackspaceButton();
                    this.animateButton(this.backspaceButton);
                }
            }
            
            updateDots() {
                this.dots.forEach((dot, index) => {
                    if (index < this.pin.length) {
                        dot.classList.add('filled');
                    } else {
                        dot.classList.remove('filled');
                    }
                    
                    if (index === this.pin.length - 1) {
                        dot.classList.add('pin-feedback');
                        setTimeout(() => {
                            dot.classList.remove('pin-feedback');
                        }, 300);
                    }
                });
            }
            
            toggleBackspaceButton() {
                if (this.pin.length > 0) {
                    this.backspaceButton.classList.add('visible');
                } else {
                    this.backspaceButton.classList.remove('visible');
                }
            }
            
            animateButton(button) {
                button.classList.add('pin-feedback');
                setTimeout(() => {
                    button.classList.remove('pin-feedback');
                }, 300);
            }
            
            reset() {
                this.pin = '';
                this.updateDots();
                this.toggleBackspaceButton();
            }
        }

        // Initialize the PIN pad when the DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            const pinPad = new PinPad({
                maxLength: 4,
                onComplete: (pin) => {
                    console.log('PIN entered:', pin);
                    // Simulate a successful login after a delay
                    setTimeout(() => {
                        alert('Login successful!');
                        pinPad.reset();
                    }, 500);
                }
            });
        });
    </script>
</body>
</html>