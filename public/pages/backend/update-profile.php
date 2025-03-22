<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../partials/header.php';

try {
    // Establish database connection and fetch user data using PDO
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user']['user_id']]);
    $user = $stmt->fetch();
    if (!$user) {
        throw new Exception("User not found.");
    }
} catch (Exception $e) {
    die("Error fetching user data: " . $e->getMessage());
}
?>

<body>
    <main class="container py-4">
        <!-- Page Header -->
        <header class="text-center mb-5">
            <h5 class="fw-bold">Update Profile</h5>
        </header>

        <!-- Profile Photo -->
        <div class="photo-container mb-4">
            <img src="../<?= htmlspecialchars($user['photo']); ?>" alt="Profile Photo" class="avatar avatar-xl border-0">
            <input type="file" id="photo-input" accept="image/*">
            <!-- <div class="upload-icon">+</div> -->
        </div>

        <!-- Update Profile Form -->
        <form id="profileForm" class="form-container">
            <!-- First Name -->

            <div class="row">


                <div class="mb-3 col-lg-6">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="first_name" class="form-control input" value="<?= htmlspecialchars($user['first_name']); ?>" disabled>
                </div>

                <!-- Last Name -->
                <div class="mb-3 col-lg-6">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="last_name" class="form-control input" value="<?= htmlspecialchars($user['last_name']); ?>" disabled>
                </div>

                <!-- Email -->
                <div class="mb-3 col-lg-6">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control input" value="<?= htmlspecialchars($user['email']); ?>" disabled>
                </div>

                <!-- Phone Number -->
                <div class="mb-3 col-lg-6">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" id="phoneNumber" name="phone_number" class="form-control input" value="<?= htmlspecialchars($user['phone_number']); ?>" disabled>
                </div>

                <!-- City -->
                <div class="mb-3 col-lg-6">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control input" value="<?= htmlspecialchars($user['city']); ?>" disabled>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn primary-btn w-100">
                <i class="fa fa-spinner fa-spin d-none" id="spinner-icon"></i>
                <span class="button-text">Update Password</span>
            </button>
        </form>
        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>
    <footer class="my-4">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All rights reserved.</p>
    </footer>

    <script>
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