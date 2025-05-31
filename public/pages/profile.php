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
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label>First Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>Last Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['phone_number']) ?>" disabled>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="button" class="disabled-btn" disabled>Update</button>
                </div>
            </form>

            <!-- Step 2: Account -->
            <form class="wizard-step d-none" id="step-account">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($user['w_bank_name']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Account Number</label>
                        <input type="number" name="account_number" maxlength="11" class="form-control" placeholder="10-digit NUBAN" value="<?= htmlspecialchars($user['w_account_number']) ?>">
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn primary-btn">Update</button>
                </div>
            </form>

            <!-- Step 3: Address -->
            <form class="wizard-step d-none" id="step-address">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label>State</label>
                        <select name="state" id="state" class="select-state form-control" required data-selected="<?= $selectedState ?>">
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
                        <select name="lga" id="lga" class="select-lga form-control" required data-selected="<?= $selectedLGA ?>">
                            <option value="">-- LGA --</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Country</label>
                        <input type="text" name="country" class="form-control" value="Nigeria" disabled>
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

    <script src="../assets/js/state-capital.js"></script>
    <script src="../assets/js/ajax.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stepForms = document.querySelectorAll('.wizard-step');
        const stepCircles = document.querySelectorAll('.step-indicator');
        const stepIds = ['biodata', 'account', 'address'];

        let currentStep = 0;

        function updateStepUI(index) {
            stepForms.forEach((form, i) => {
                form.classList.toggle('d-none', i !== index);
            });

            stepCircles.forEach((circle, i) => {
                const stepCircle = circle.querySelector('.step-circle');
                stepCircle.classList.toggle('bg-gradient-dark', i === index);
                stepCircle.classList.toggle('text-white', i === index);
                stepCircle.classList.toggle('bg-gradient-light', i !== index);
                stepCircle.classList.toggle('text-dark', i !== index);
            });

            currentStep = index;
        }

        window.goToStep = function (index) {
            console.log(`Navigating to step ${index}`);
            updateStepUI(index);
        };


        // Initialize first step
        updateStepUI(0);

        // AJAX submission using your custom sendAjaxRequest
        document.querySelectorAll('form.wizard-step').forEach((form, index) => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const step = stepIds[index];
                const formData = new FormData(form);
                formData.append('step', step);

                const params = new URLSearchParams();
                for (let pair of formData.entries()) {
                    params.append(pair[0], pair[1]);
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
