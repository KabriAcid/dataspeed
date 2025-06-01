<!-- PIN Modal -->
<div id="pinModal" class="modal-overlay" style="display: none;">
  <div class="pin-container">

    <!-- Icon / Header -->
    <div class="icon-section">
      <i class="fas fa-shield-check check-icon fa-2x"></i>
      <p class="mt-2 mb-0 fw-bold">Enter Transaction PIN</p>
    </div>

    <!-- PIN Dots -->
    <div class="pin-section">
      <div class="pin-dots">
        <div class="pin-dot"></div>
        <div class="pin-dot"></div>
        <div class="pin-dot"></div>
        <div class="pin-dot"></div>
      </div>
    </div>

    <!-- Keypad -->
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
        <button class="key-button" data-value="0">0</button>
        <button id="backspace" class="key-backspace">
          <i class="fas fa-delete-left"></i>
        </button>
      </div>
    </div>

    <!-- Actions (optional) -->
    <div class="modal-actions">
      <button class="forgot">Forgot PIN?</button>
      <button class="logout">Logout</button>
    </div>
  </div>
</div>
