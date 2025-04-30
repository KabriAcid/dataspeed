<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../partials/header.php';
require __DIR__ . '/../../../functions/functions.php';

// Check if user session exists
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['user_id'];
    $username = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];
    $user_location = $_SESSION['user']['city'] ?? 'Nigeria';
} else {
    header('Location: login.php');
}

// Retrieving user referral details
$referrals = getUserReferralDetails($pdo, $user_id)[0] ?? null;

?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Referrrals</h5>
        </header>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 d-lg-block d-md-none d-sm-none">
                    <h2>Refer friends and <br><span>earn</span> rewards</h2>
                    <p>Invite your friends to <span class="brand-name">Dataspeed</span> and earn instant
                        <b>&#8358;100</b>
                        airtime
                        rewards.
                    </p>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="container py-0">
                        <form action="" method="get">
                            <!-- 2 forms fields, one for referrals code and a referral link-->
                            <h3>Referral Link & Code</h3>
                            <div class="form-group">
                                <label for="referralCode">Referral Code</label>
                                <input type="text" id="referralCode" class="form-control"
                                    value="<?php echo $referrals['referral_code']; ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label for="referralLink">Referral Link</label>
                                <input type="text" id="referralLink" class="form-control"
                                    value="<?php echo $referrals['referral_link']; ?>" readonly />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>
    <footer class=" my-4">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All
            rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>