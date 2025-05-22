<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <main class="container-fluid px-2 py-4">
        <!-- Page Header -->
        <header class="text-center mb-5">
            <h5 class="fw-bold">My Profile</h5>
        </header>

        <!-- Profile Photo -->
        <div class="photo-container mb-4">
            <img src="../<?= htmlspecialchars($user['photo']); ?>" alt="Profile Photo"
                class="avatar avatar-xl border-0">
            <input type="file" id="photo-input" accept="image/*">
            <!-- <div class="upload-icon">+</div> -->
        </div>

        <!-- Profile Wizard UI -->
        <!-- Bootstrap Multi-Step Wizard with Step Tracker -->
        <div class="container py-5">
            <form id="profileWizardForm" method="post" action="update_profile_handler.php">

                <!-- Step Tracker -->
                <div class="d-flex justify-content-between mb-4">
                    <div class="step-indicator text-center flex-fill">
                        <div class="step-circle bg-gradient-dark text-white mx-auto">1</div>
                        <small class="step-label">About</small>
                    </div>
                    <div class="step-indicator text-center flex-fill">
                        <div class="step-circle bg-gradient-light text-dark mx-auto">2</div>
                        <small class="step-label">Account</small>
                    </div>
                    <div class="step-indicator text-center flex-fill">
                        <div class="step-circle bg-gradient-light text-dark mx-auto">3</div>
                        <small class="step-label">Address</small>
                    </div>
                </div>

                <!-- Step 1: About -->
                <div class="wizard-step" id="step-about">
                    <!-- <h5 class="mb-3">Let's start with the basic information</h5> -->

                    <div class="form-group mb-3">
                        <label>First Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>"
                            readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>Last Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>"
                            readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>"
                            readonly>
                    </div>

                    <!-- Button  should be at the end -->
                    <button type="button" class="btn btn-primary text-end" onclick="nextStep()">Next</button>
                </div>

                <!-- Step 2: Account -->
                <div class="wizard-step d-none" id="step-account">
                    <h5 class="mb-3">Update Your Account</h5>

                    <div class="form-group mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control"
                            value="<?= htmlspecialchars($user['username']) ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control">
                    </div>

                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                </div>

                <!-- Step 3: Address -->
                <div class="wizard-step d-none" id="step-address">
                    <h5 class="mb-3">Your Address</h5>

                    <div class="form-group mb-3">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control"
                            value="<?= htmlspecialchars($user['address']) ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label>City</label>
                        <input type="text" name="city" class="form-control"
                            value="<?= htmlspecialchars($user['city']) ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label>State</label>
                        <input type="text" name="state" class="form-control"
                            value="<?= htmlspecialchars($user['state']) ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label>Country</label>
                        <input type="text" name="country" class="form-control"
                            value="<?= htmlspecialchars($user['country']) ?>">
                    </div>

                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>

        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>
    <footer class="my-4">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
    </footer>
    <script>
    let currentStep = 0;
    const steps = document.querySelectorAll(".wizard-step");
    const indicators = document.querySelectorAll(".step-circle");

    function showStep(index) {
        steps.forEach((step, i) => {
            step.classList.toggle("d-none", i !== index);
            indicators[i].classList.toggle("bg-primary", i === index);
            indicators[i].classList.toggle("text-white", i === index);
            indicators[i].classList.toggle("bg-light", i !== index);
            indicators[i].classList.toggle("text-dark", i !== index);
        });
        currentStep = index;
    }

    function nextStep() {
        if (currentStep < steps.length - 1) {
            showStep(currentStep + 1);
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            showStep(currentStep - 1);
        }
    }

    // Initialize
    showStep(0);
    // Handle Profile Photo Upload
    document.querySelector('.photo-container').addEventListener('click', () => {
        document.getElementById('photo-input').click();
    });

    document.getElementById('photo-input').addEventListener('change', function() {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-photo').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    });

    // Handle Form Submission Using AJAX
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData();
        const photoInput = document.getElementById('photo-input');
        if (photoInput.files[0]) {
            formData.append('photo', photoInput.files[0]);
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update-profile.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    alert(response.message || 'Profile updated successfully!');
                } catch (error) {
                    alert('An error occurred while updating your profile.');
                }
            }
        };
        xhr.send(formData);
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>