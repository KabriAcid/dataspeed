<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';

$referrals = getUserReferralDetails($pdo, $user_id);
$rewards = getReferralRewards($pdo, $user_id);
$pendingReferrals = getReferralsByStatus($pdo, $user_id, 'pending');
$completedReferrals = getReferralsByStatus($pdo, $user_id, 'claimed');

?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Referrrals</h5>
        </header>

        <div>
            <div class="row mb-5">
                <!-- Pending Reward -->
                <div class="col-6">
                    <div class="card px-3 py-2">
                        <div class="card-header mb-0 p-0">
                            <p class="text-sm mb-0 font-weight-bold text-secondary">Pending Reward</p>
                        </div>
                        <div class="card-body p-0">
                            <h4 class="amount mb-0 text-success font-weight-bolder">&#8358;
                                <?= number_format($rewards['pending'], 2) ?></h4>
                        </div>
                    </div>
                </div>
                <!-- Claimed reward -->
                <div class="col-6">
                    <div class="card px-3 py-2">
                        <div class="card-header mb-0 p-0">
                            <p class="text-sm mb-0 font-weight-bold text-secondary">Claimed Reward</p>
                        </div>
                        <div class="card-body p-0">
                            <h4 class="amount mb-0 text-dark font-weight-bolder">&#8358;
                                <?= number_format($rewards['claimed'], 2) ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABS -->
            <div class="tabs" style="margin-bottom: 250px;">
                <div class="tab-buttons">
                    <button class="tab-btn active" data-tab="pending" onclick="showTab(event)">Pending</button>
                    <button class="tab-btn" data-tab="completed" onclick="showTab(event)">Completed</button>
                </div>

                <div class="tab-content" id="pending">
                    <p>This is the <strong>Pending</strong> content.</p>
                </div>

                <div class="tab-content hidden" id="completed">
                    <p>This is the <strong>Completed</strong> content.</p>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-6 col-xl-4">
                    <div class="d-flex justify-content-start align-items-center">
                        <!-- Copy Code -->
                        <div class="referral-code shadow-sm px-3 py-2 rounded bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="">
                                    <p class="text-sm mb-0 text-secondary font-weight-bold no-wrap">Referral Code</p>
                                    <h4 class="font-weight-bolder primary mb-1 letter-normal fs-6" id="referral_code">
                                        <?= $referrals['referral_code'] ?></h4>
                                </div>
                                <div class="shadow bg-white h-100 w-100 rounded ms-5">
                                    <i class="fa fa-copy px-2 px-1 cursor-pointer fs-5" id="copy-icon"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Copy link -->
                        <?php
                        $referralLink = $referrals['referral_link'] ?? ''; // from your PHP logic
                        ?>
                        <div class="referral-link rounded mx-3 d-block">
                            <input type="text" id="referralLinkInput" value="<?= htmlspecialchars($referralLink) ?>"
                                readonly hidden>
                            <button type="button" id="copyText" class="btn mb-0 primary-btn no-wrap">Copy
                                Link</button>
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
    <script>
    document.getElementById("copyText").addEventListener("click", function() {
        const linkInput = document.getElementById("referralLinkInput").value;
        // Copy to clipboard after clicking the button
        navigator.clipboard.writeText(linkInput).then(function() {
            console.log(linkInput)
        }, function(err) {
            console.error("Could not copy text: ", err);
        });
    });
    document.getElementById('copy-icon').addEventListener('click', function() {
        const referralCode = document.getElementById('referral_code').innerText.trim();


        // Copy to clipboard
        navigator.clipboard.writeText(referralCode).then(() => {
            const copyBtn = document.getElementById('copy-icon');
            // Change icon to checkmark and color to green
            copyBtn.classList.remove('fa-copy');
            copyBtn.classList.add('fa-check', 'text-success');

            // Revert back after 2 seconds
            setTimeout(() => {
                copyBtn.classList.remove('fa-check', 'text-success');
                copyBtn.classList.add('fa-copy', 'text-primary');
            }, 3000);
        }).catch(err => {
            console.error('Copy failed: ', err);
        });
    });

    function showTab(event) {
        const tabId = event.target.getAttribute("data-tab");

        // Hide all contents
        document.querySelectorAll(".tab-content").forEach(content => {
            content.classList.add("hidden");
        });

        // Remove 'active' from all buttons
        document.querySelectorAll(".tab-btn").forEach(button => {
            button.classList.remove("active");
        });

        // Show the selected tab
        document.getElementById(tabId).classList.remove("hidden");

        // Add 'active' to the clicked button
        event.target.classList.add("active");
    }

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
                window.location.reload(); // Refresh the page to show updated data
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