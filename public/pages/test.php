<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Settings</title>
    <link rel="stylesheet" href="../assets/css/creative-tim.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
      
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Security Settings</h1>
        </div>

        <div class="tabs">
            <button class="tab-button active" data-tab="password">
                <i data-lucide="lock"></i>
                <span>Password</span>
            </button>
            <button class="tab-button" data-tab="pin">
                <i data-lucide="key"></i>
                <span>PIN</span>
            </button>
        </div>

        <div class="form-container">
            <div class="success-message" style="display: none;">
                <i data-lucide="check-circle-2"></i>
                <span>Successfully updated!</span>
            </div>

            <form id="securityForm">
                <div class="tab-content active" id="password-tab">
                    <div class="form-group">
                        <label>New Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="newPassword" class="form-control" required />
                            <button type="button" class="toggle-password">
                                <i data-lucide="eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirmPassword" class="form-control" required />
                    </div>
                </div>

                <div class="tab-content" id="pin-tab">
                    <div class="form-group">
                        <label>New PIN</label>
                        <input type="password" name="newPin" inputmode="numeric" pattern="\d{4}" maxlength="4" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Confirm PIN</label>
                        <input type="password" name="confirmPin" inputmode="numeric" pattern="\d{4}" maxlength="4" class="form-control" required />
                    </div>
                </div>

                <button type="submit" class="submit-button">
                    <span class="button-text">Update Password</span>
                    <div class="spinner" style="display: none;"></div>
                </button>
            </form>
        </div>
    </div>

    <script>

        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                button.classList.add('active');
                document.getElementById(`${button.dataset.tab}-tab`).classList.add('active');
                document.querySelector('.button-text').textContent = `Update ${button.dataset.tab.charAt(0).toUpperCase() + button.dataset.tab.slice(1)}`;
            });
        });

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = button.previousElementSibling;
                input.type = input.type === 'password' ? 'text' : 'password';
                button.querySelector('i').setAttribute('data-lucide', input.type === 'password' ? 'eye' : 'eye-off');
                lucide.createIcons();
            });
        });

        document.getElementById('securityForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            sendAjaxRequest('update-passcodes.php', 'POST', formData, function(response) {
                document.querySelector('.success-message').style.display = 'flex';
            });
        });
    </script>
</body>

</html>