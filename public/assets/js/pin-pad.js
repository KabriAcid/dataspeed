function initPinPad(containerSelector, onComplete) {
  let pin = '';
  const maxLength = 4;
  const container = document.querySelector(containerSelector);

  if (!container) return;

  const dotEls = Array.from(container.querySelectorAll('.pin-dot'));

  function updateDots() {
    dotEls.forEach((dot, i) => {
      dot.classList.toggle('filled', i < pin.length);
    });
  }

  function addDigit(digit) {
    if (pin.length < maxLength) {
      pin += digit;
      updateDots();
      if (pin.length === maxLength) {
        setTimeout(() => {
          onComplete(pin);
          resetPin();
        }, 300);
      }
    }
  }

  function removeDigit() {
    pin = pin.slice(0, -1);
    updateDots();
  }

  function resetPin() {
    pin = '';
    updateDots();
  }

  // Attach events locally to this container only
  container.querySelectorAll('.key-button').forEach(button => {
    button.addEventListener('click', () => {
      addDigit(button.getAttribute('data-value'));
    });
  });

  container.querySelector('#backspace').addEventListener('click', removeDigit);

  container.querySelector('.logout')?.addEventListener('click', () => {
    alert('Logging out...');
  });

  container.querySelector('.forgot')?.addEventListener('click', () => {
    alert('Forgot PIN clicked');
  });

  // Optional: Enable keyboard input
  document.addEventListener('keydown', function handleKeyPress(e) {
    if (!container.matches(':visible')) return;
    if (/^[0-9]$/.test(e.key)) {
      addDigit(e.key);
    } else if (e.key === 'Backspace') {
      removeDigit();
    }
  });
}
