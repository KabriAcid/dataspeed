<!-- PIN Modal -->
<div id="pinModal" class="modal-overlay">
  <div class="pin-modal-content">

    <!-- Header -->
    <div class="pin-header">
      <span class="pin-title">Enter PIN</span>
      <button class="pin-close-btn" onclick="closePinModal()">×</button>
    </div>

    <!-- PIN Dots -->
    <div class="pin-dots">
      <div class="dot" id="dot1"></div>
      <div class="dot" id="dot2"></div>
      <div class="dot" id="dot3"></div>
      <div class="dot" id="dot4"></div>
    </div>

    <p class="forgot-pin">Forgot PIN?</p>

    <!-- Keypad -->
    <div class="pin-keypad">
      ${[1,2,3,4,5,6,7,8,9,'',0,'←'].map((val, i) => `
        <button class="key" onclick="handleKey('${val}')">${val === '←' ? '⌫' : val}</button>
      `).join('')}
    </div>
  </div>
</div>
