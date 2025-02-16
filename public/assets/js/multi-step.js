function nextStep(step) {
    document.querySelectorAll('.step').forEach(s => s.classList.add('d-none'));
    document.getElementById('step' + step).classList.remove('d-none');
    document.getElementById('stepIndicator').textContent = `Step ${step}/3`;
}

function validateEmail() {
    const email = document.getElementById('email').value;
    const emailError = document.getElementById('emailError');
    const emailField = document.getElementById('email');

    fetch('validate_email.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `email=${email}`
    })
        .then(response => response.text())
        .then(data => {
            if (data === 'valid') {
                nextStep(2);
            } else {
                emailField.classList.add('error');
                emailError.classList.remove('d-none');
            }
        });
}