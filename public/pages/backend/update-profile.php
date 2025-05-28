<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <main class="container-fluid px-2 py-4">
        <header class="text-center mb-4">
            <h5 class="fw-bold">My Profile</h5>
        </header>

        <!-- Profile Photo -->
        <div class="photo-container position-relative mb-4" style="width: max-content;">
            <img src="../<?= htmlspecialchars($user['photo']); ?>" alt="Profile Photo" id="profile-photo"
                class="avatar avatar-xl border-0 rounded-circle">
            <label for="photo-input"
                class="upload-icon bg-none text-white rounded-circle d-flex align-items-center justify-content-center"
                style="position: absolute; bottom: 0; right: 0; width: 30px; height: 30px; cursor: pointer;">
                <i class="fa fa-plus text-sm"></i>
            </label>
            <input type="file" id="photo-input" accept="image/*" style="display: none;">
        </div>

        <div class="form-container">
            <!-- Step Tracker -->
            <div class="d-flex justify-content-between mb-4">
                <div class="step-indicator text-center flex-fill" onclick="goToStep(0)">
                    <div class="step-circle bg-gradient-dark text-white mx-auto">1</div>
                    <small class="step-label">Biodata</small>
                </div>
                <div class="step-indicator text-center flex-fill" onclick="goToStep(1)">
                    <div class="step-circle bg-gradient-light text-dark mx-auto">2</div>
                    <small class="step-label">Account</small>
                </div>
                <div class="step-indicator text-center flex-fill" onclick="goToStep(2)">
                    <div class="step-circle bg-gradient-light text-dark mx-auto">3</div>
                    <small class="step-label">Address</small>
                </div>
            </div>

            <!-- Step 1: About -->
            <form class="wizard-step" id="step-about">
                <div class="row">
                    <div class="col-md-6">
                        <label>First Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>"
                            readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Last Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>"
                            readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>"
                            readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['phone_number']) ?>"
                            readonly>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="button" class="disabled-btn" disabled>Update</button>
                </div>
            </form>

            <!-- Step 2: Account -->
            <form class="wizard-step d-none" id="step-account">
                <div class="row">
                    <div class="col-md-6">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="form-control"
                            value="<?= htmlspecialchars($user['w_bank_name']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Account Number</label>
                        <input type="number" name="account_number" maxlength="11" class="form-control"
                            placeholder="10-digit NUBAN" value="<?= htmlspecialchars($user['w_account_number']) ?>">
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn primary-btn">Update</button>
                </div>
            </form>

            <!-- Step 3: Address -->
            <form class="wizard-step d-none" id="step-address">
                <div class="row">
                    <div class="col-md-6">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control"
                            value="<?= htmlspecialchars($user['address']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>City</label>
                        <input type="text" name="city" class="form-control"
                            value="<?= htmlspecialchars($user['city']) ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>State</label>
                        <input type="text" name="state" class="form-control"
                            value="<?= htmlspecialchars($user['state']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Country</label>
                        <input type="text" name="country" class="form-control"
                            value="<?= htmlspecialchars($user['country']) ?? 'Nigeria' ?>">
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn primary-btn">Update</button>
                </div>
            </form>

        </div>
        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const stepForms = document.querySelectorAll('.wizard-step');
        const stepCircles = document.querySelectorAll('.step-circle');
        const stepIds = ['about', 'account', 'address'];
        let currentStep = 0;

        function updateStepUI(index) {
            stepForms.forEach((form, i) => {
                form.classList.toggle('d-none', i !== index);
                stepCircles[i].classList.toggle('bg-gradient-dark', i === index);
                stepCircles[i].classList.toggle('text-white', i === index);
                stepCircles[i].classList.toggle('bg-gradient-light', i !== index);
                stepCircles[i].classList.toggle('text-dark', i !== index);
            });
            currentStep = index;
        }

        window.goToStep = function(index) {
            updateStepUI(index);
        };

        // Initial step
        updateStepUI(0);

        // AJAX form submission
        document.querySelectorAll('.wizard-step form, .wizard-step').forEach((form, index) => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const step = stepIds[index];
                const formData = new FormData(form);
                formData.append('step', step);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update-profile.php', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.onload = function() {
                    try {
                        const res = JSON.parse(xhr.responseText);
                        showToasted(res.message || 'Update complete.', res.success ?
                            'success' : 'error');
                    } catch (e) {
                        showToasted('Invalid server response.', 'error');
                    }
                };

                xhr.onerror = function() {
                    showToasted('Network error occurred.', 'error');
                };

                xhr.send(formData);
            });
        });

    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>