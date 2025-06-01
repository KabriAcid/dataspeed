<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';

$selectedState = $user['state'] ?? '';
$selectedLGA = $user['city'] ?? '';
?>

<body>
    <main class="container-fluid px-2 py-4">
        <header class="text-center mb-4">
            <h5 class="fw-bold">My Profile</h5>
        </header>

        <!-- Profile Photo -->
        <div class="photo-container position-relative mb-4" style="width: max-content;">
            <img src="<?= htmlspecialchars($user['photo']); ?>" alt="Profile Photo" id="profile-photo"
                class="avatar avatar-xl border-0 rounded-circle">
            <input type="file" id="photo-input" accept="image/*" style="display: none;">
        </div>

        <div class="form-container">
            <!-- Step Tracker -->
            <div class="d-flex justify-content-between py-4 px-0 step-tracker">
                <div class="step-indicator text-center flex-fill" data-step="0">
                    <div class="step-circle bg-gradient-dark text-white mx-auto">1</div>
                    <small class="step-label">Biodata</small>
                </div>
                <div class="step-indicator text-center flex-fill" data-step="1">
                    <div class="step-circle bg-gradient-light text-dark mx-auto">2</div>
                    <small class="step-label">Account</small>
                </div>
                <div class="step-indicator text-center flex-fill" data-step="2">
                    <div class="step-circle bg-gradient-light text-dark mx-auto">3</div>
                    <small class="step-label">Address</small>
                </div>
            </div>

            <!-- Step 1: Biodata -->
            <form class="wizard-step active" id="step-biodata">
                <div class="row mb-1">
                    <div class="col-md-6 mb-3">
                        <label>First Name</label>
                        <input type="text" class="input" value="<?= htmlspecialchars($user['first_name']) ?? '' ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>Last Name</label>
                        <input type="text" class="input" value="<?= htmlspecialchars($user['last_name']) ?? '' ?>" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" class="input" value="<?= htmlspecialchars($user['email']) ?? '' ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>Phone Number</label>
                        <input type="text" class="input" value="<?= htmlspecialchars($user['phone_number']) ?? '' ?>" disabled>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="button" class="disabled-btn" disabled>Update</button>
                </div>
            </form>

            <!-- Step 2: Account -->
            <form class="wizard-step" id="step-account">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" placeholder="e.g OPay" class="input" value="<?= htmlspecialchars($user['w_bank_name']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Account Number</label>
                        <input type="text"
                            id="account_number"
                            name="account_number"
                            maxlength="10"
                            inputmode="numeric"
                            pattern="\d*"
                            class="input"
                            placeholder="10-digit NUBAN"
                            value="<?= htmlspecialchars($user['w_account_number']) ?>"
                            >

                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn primary-btn">Update</button>
                </div>
            </form>

            <!-- Step 3: Address -->
            <form class="wizard-step" id="step-address">
                <div class="row mb-1">
                    <div class="col-md-6 mb-3">
                        <label>State</label>
                        <select name="state" id="state" class="select-state input" required data-selected="<?= $selectedState ?>">
                            <option value="">-- State --</option>
                            <option value="Abia">Abia</option>
                            <option value="Adamawa">Adamawa</option>
                            <option value="AkwaIbom">Akwa Ibom</option>
                            <option value="Anambra">Anambra</option>
                            <option value="Bauchi">Bauchi</option>
                            <option value="Bayelsa">Bayelsa</option>
                            <option value="Benue">Benue</option>
                            <option value="Borno">Borno</option>
                            <option value="Cross River">Cross River</option>
                            <option value="Delta">Delta</option>
                            <option value="Ebonyi">Ebonyi</option>
                            <option value="Edo">Edo</option>
                            <option value="Ekiti">Ekiti</option>
                            <option value="Enugu">Enugu</option>
                            <option value="Gombe">Gombe</option>
                            <option value="Imo">Imo</option>
                            <option value="Jigawa">Jigawa</option>
                            <option value="Kaduna">Kaduna</option>
                            <option value="Kano">Kano</option>
                            <option value="Katsina">Katsina</option>
                            <option value="Kebbi">Kebbi</option>
                            <option value="Kogi">Kogi</option>
                            <option value="Kwara">Kwara</option>
                            <option value="Lagos">Lagos</option>
                            <option value="Nasarawa">Nasarawa</option>
                            <option value="Niger">Niger</option>
                            <option value="Ogun">Ogun</option>
                            <option value="Ondo">Ondo</option>
                            <option value="Osun">Osun</option>
                            <option value="Oyo">Oyo</option>
                            <option value="Plateau">Plateau</option>
                            <option value="Rivers">Rivers</option>
                            <option value="Sokoto">Sokoto</option>
                            <option value="Taraba">Taraba</option>
                            <option value="Yobe">Yobe</option>
                            <option value="Zamfara">Zamfara</option>
                            <option value="FCT">FCT</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>City</label>
                        <select name="lga" id="lga" class="select-lga input" required data-selected="<?= $selectedLGA ?>">
                            <option value="">-- LGA --</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Address</label>
                        <input type="text" name="address" class="input" placeholder="Home Address" value="<?= htmlspecialchars($user['address']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Country</label>
                        <input type="text" name="country" class="input" value="Nigeria" disabled>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn primary-btn">Update</button>
                </div>
            </form>
        </div>

        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
    </footer>

    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/state-capital.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stepForms = document.querySelectorAll('.wizard-step');
            const stepIndicators = document.querySelectorAll('.step-indicator');
            const stepIds = ['biodata', 'account', 'address'];

            let currentStep = 0;

            // Show toast message when the user clicks on the disabled button
            document.querySelector('.disabled-btn').addEventListener('click', function () {
                console.log('❌ Attempted to update biodata, but it is not editable.');
                showToasted('This step is not editable yet.', 'info');
            });
            /**
             * Show the form for the selected step index
             */
            function updateStepUI(index) {

                // Show the correct form and hide the others
                stepForms.forEach((form, i) => {
                    if (i === index) {
                        form.classList.remove('d-none');
                        form.style.display = 'block';
                        console.log(`✅ Showing form: #${form.id}`);
                    } else {
                        form.classList.add('d-none');
                        form.style.display = 'none';
                    }
                });

                // Update step circle styles
                stepIndicators.forEach((indicator, i) => {
                    const circle = indicator.querySelector('.step-circle');
                    circle.classList.toggle('bg-gradient-dark', i === index);
                    circle.classList.toggle('text-white', i === index);
                    circle.classList.toggle('bg-gradient-light', i !== index);
                    circle.classList.toggle('text-dark', i !== index);
                });

                currentStep = index;
            }

            /**
             * When a step indicator is clicked
             */
            stepIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    updateStepUI(index);
                });
            });

            // Make function globally available if needed
            window.goToStep = function (index) {
                updateStepUI(index);
            };

            // Initialize first step
            updateStepUI(currentStep);

            /**
             * Handle form submissions using your custom AJAX logic
             */
            document.querySelectorAll('form.wizard-step').forEach((form, index) => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const step = stepIds[index];
                    const formData = new FormData(form);
                    formData.append('step', step);

                    const params = new URLSearchParams();
                    for (let [key, value] of formData.entries()) {
                        params.append(key, value);
                    }


                    sendAjaxRequest('update-address.php', 'POST', params.toString(), function (res) {
                        if (res.success) {
                            showToasted(res.message || 'Update successful', 'success');
                        } else {
                            showToasted(res.message || 'Update failed', 'error');
                        }
                    });
                });
            });
        });
        </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
