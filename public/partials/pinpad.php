<!-- PIN Modal -->
<div id="pinpadModal" class="modal-overlay" style="display: none;"
  data-amount="" data-phone="" data-network="" data-type="" data-action="" data-plan-id="" data-iuc-number="">
  <div class="pin-container">
    <div class="pin-header text-center">
      <img src="<?= $user['photo']; ?>" alt="avatar" class="pinpad-avatar">
      <h3><?= ucwords($user['user_name']); ?></h3>
    </div>
    <div class="pin-field">
      <div class="icon-section text-center">
        <i class="nc-icon nc-check-circle check-icon fa-2x"></i>
        <p class="mt-2 mb-0 fw-bold"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-alertGreen size-4">
            <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"></path>
          </svg> <span class="me-2">Enter Transaction PIN</span></p>
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