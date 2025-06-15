<!-- PIN Modal -->
<div id="pinpadModal" class="modal-overlay" style="display: none;" data-amount="" data-phone="" data-network="" data-type="" data-action="">
  <div class="pin-container">
    <div class="pin-header text-center">
      <img src="<?= $user['photo']; ?>" alt="avatar" class="pinpad-avatar">
      <h3><?= $user['first_name']; ?></h3>
    </div>
    <div class="pin-field">
      <div class="icon-section text-center">
        <i class="nc-icon nc-check-circle check-icon fa-2x"></i>
        <p class="mt-2 mb-0 fw-bold">Enter Transaction PIN</p>
      </div>
      <div class="pin-section">
        <div class="pin-dots">
          <div class="pin-dot"></div>
          <div class="pin-dot"></div>
          <div class="pin-dot"></div>
          <div class="pin-dot"></div>
        </div>
      </div>
    </div>
    <div class="pin-keypad">
      <div class="keypad-row">
        <button type="button" class="key-button" data-value="1">1</button>
        <button type="button" class="key-button" data-value="2">2</button>
        <button type="button" class="key-button" data-value="3">3</button>
      </div>
      <div class="keypad-row">
        <button type="button" class="key-button" data-value="4">4</button>
        <button type="button" class="key-button" data-value="5">5</button>
        <button type="button" class="key-button" data-value="6">6</button>
      </div>
      <div class="keypad-row">
        <button type="button" class="key-button" data-value="7">7</button>
        <button type="button" class="key-button" data-value="8">8</button>
        <button type="button" class="key-button" data-value="9">9</button>
      </div>
      <div class="keypad-row">
        <button type="button" class="key-spacer key-button invisible"></button>
        <button type="button" class="key-button" data-value="0">0</button>
        <button type="button" id="backspace" class="key-button key-backspace" aria-label="Backspace">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#1A202C" style="width: 1.1em; height: 1.1em;">
            <path fill-rule="evenodd" d="M2.515 10.674a1.875 1.875 0 000 2.652L8.89 19.7c.352.351.829.549 1.326.549H19.5a3 3 0 003-3V6.75a3 3 0 00-3-3h-9.284c-.497 0-.974.198-1.326.55l-6.375 6.374zM12.53 9.22a.75.75 0 10-1.06 1.06L13.19 12l-1.72 1.72a.75.75 0 101.06 1.06l1.72-1.72 1.72 1.72a.75.75 0 101.06-1.06L15.31 12l1.72-1.72a.75.75 0 10-1.06-1.06l-1.72 1.72-1.72-1.72z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    </div>
    <div class="pin-action-buttons d-flex justify-content-between mt-3">
      <button type="button" id="pin-exit-btn" id="closePinpad"><i class="fa fa-home"></i> Exit</button>
      <button type="button" id="pin-forgot-btn"><i class="fa fa-question-mark"></i> Forgot PIN</button>
    </div>
  </div>

  <!-- Overlay -->
  <div id="bodyOverlay" class="body-overlay" style="display: none;">
    <div class="overlay-spinner"></div>
  </div>
</div>