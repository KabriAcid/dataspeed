<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../partials/header.php';
require __DIR__ . '/../../../functions/Model.php';

// Check if user session exists
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['user_id'];
    $username = $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'];
} else {
    header('Location: login.php');
}

$stmt = $pdo->prepare("SELECT * FROM referrals WHERE user_id = ?");
$stmt->execute([$user_id]);
$referrals = $stmt->fetch();

?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Referrrals</h5>
        </header>

        <div class="container-fluid">
            <div class="row removable mb-5">
                <div class="col-xl-4 col-sm-6">
                    <div class="card mb-3 mb-xl-0">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Referrals</p>
                                        <h6 class="font-weight-bolder mb-0">
                                            <?php
                                            $stmt = $pdo->prepare("SELECT COUNT(*) as total_referrals FROM referrals WHERE user_id = ?");
                                            $stmt->execute([$user_id]);
                                            $total_referrals = $stmt->fetch();
                                            ?>
                                            <span>
                                                <?php echo $total_referrals['total_referrals']; ?>
                                            </span>
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                        <i class="fa fa-single-02 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current reward  -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Reward</p>
                                        <h6 class="font-weight-bolder mb-0">
                                            <?php
                                            $stmt = $pdo->prepare("SELECT SUM(reward) as total_reward FROM referrals WHERE user_id = ? AND status = 'pending'");
                                            $stmt->execute([$user_id]);
                                            $total_reward = $stmt->fetch();

                                            ?>
                                            <span>
                                                <?php echo '&#8358;' . ($total_reward['total_reward'] ?? 0.00); ?>
                                            </span>
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="text-center">
                                        <form action="" method="post" onsubmit="return false;">
                                            <?php
                                            // Check if status is not pending then deactivate the button
                                            if ($referrals['status'] == 'pending') {
                                                echo '<button type="submit" class="btn btn-success btn-sm bold"  onclick="claimReward()">Claim</button>';
                                            } else {
                                                echo '<button type="submit" class="btn btn-secondary" disabled>Deactivated</button>';
                                            }

                                            ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Current Session  -->
                <div class="col-xl-4 col-sm-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Reward Status</p>
                                        <h6 class="font-weight-bolder mb-0">
                                            <span class="text-capitalize">
                                                <?php echo $referrals['status'] ?? 'Unavailable'; ?>
                                            </span>
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                        <i class="fa fa-calendar-grid-58 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" row">
                <div class="col-lg-6 d-lg-block mb-5">
                    <h3>Refer friends and <br><span>earn</span> rewards</h3>
                    <p class="text-sm">Invite your friends to <span class="brand-name">Dataspeed</span> and earn
                        instant
                        <b>&#8358;100</b>
                        airtime
                        rewards.
                    </p>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="container py-0">
                        <form action="" method="get">
                            <!-- 2 forms fields, one for referrals code and a referral link-->
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
            <div class="container">
                <div class="d-flex justify-content-center">

                </div>
            </div>
        </div>



        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>
    <footer class=" my-4">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamcodes 2025. All
            rights reserved.</p>
    </footer>
    <script>
    function sendAjaxRequest(url, method, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    callback(response);
                } catch (error) {
                    callback({
                        success: false,
                        message: "Invalid JSON response"
                    });
                }
            }
        };
        xhr.send(data);
    }

    function claimReward() {
        sendAjaxRequest('claim_reward.php', 'POST', '', function(response) {
            if (response.success) {
                alert(response.message);
                // Optionally refresh UI or update reward display
            } else {
                console.log('Error: ' + response.message);
            }
        });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>