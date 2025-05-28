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
                            <?php
                            $color = ($rewards['pending'] > 0) ? 'text-warning' : 'text-dark'
                            ?>
                            <h4 id="pendingAmount" class="amount mb-0 <?= $color; ?> font-weight-bolder">&#8358;
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
                            <?php
                            $color = ($rewards['claimed'] > 0) ? 'text-success' : 'text-dark'
                            ?>
                            <h4 id="claimedAmount" class="amount mb-0 <?= $color; ?> font-weight-bolder">&#8358;
                                <?= number_format($rewards['claimed'], 2) ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABS -->
            <div class="tabs" style="width: 100%;">
                <div class="tab-buttons">
                    <button class="tab-btn active" data-tab="pending">Pending</button>
                    <button class="tab-btn" data-tab="completed">Completed</button>
                </div>

                <div class="tab-content active" id="pending">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Reward</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pendingReferrals)) : ?>
                                <?php foreach ($pendingReferrals as $referral) : ?>
                                <?php
                                        $user = getUserInfo($pdo, $referral['user_id']);
                                        $formattedDate = date("M j, Y g:i A", strtotime($referral['created_at']));
                                        ?>
                                <tr>
                                    <td><strong>&#8358;<?= htmlspecialchars($referral['reward']); ?></strong></td>

                                    <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                    <td class="text-warning">
                                        <?= htmlspecialchars(ucfirst($referral['status'])); ?></td>
                                    <td><?= $formattedDate; ?></td>
                                    <td>
                                        <button class="btn badge bg-success claim-btn"
                                            data-id="<?= $referral['referral_id']; ?>">Claim</button>
                                    </td>

                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <tr>
                                    <td colspan=" 5" class="text-center text-muted p-5">No pending referrals found.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-content" id="completed">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Reward</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($completedReferrals)) : ?>
                                <?php foreach ($completedReferrals as $referral) : ?>
                                <?php
                                        $user = getUserInfo($pdo, $referral['user_id']);
                                        $formattedDate = date("M j, Y g:i A", strtotime($referral['created_at']));
                                        ?>
                                <tr>
                                    <td><strong>&#8358;<?= htmlspecialchars($referral['reward']); ?></strong></td>

                                    <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                    <td class="text-warning"><?= htmlspecialchars(ucfirst($referral['status'])); ?></td>
                                    <td><?= $formattedDate; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted p-5">No completed referrals found.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
            showToasted('Copied successfully', 'success');
        }, function(err) {
            showToasted('Could not copy code', 'error');
            console.error(err);
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

            showToasted('Copied successfully', 'success');

            // Revert back after 2 seconds
            setTimeout(() => {
                copyBtn.classList.remove('fa-check', 'text-success');
                copyBtn.classList.add('fa-copy', 'text-primary');
            }, 3000);
        }).catch(err => {
            showToasted('Could not copy code', 'error');
            console.error('Copy failed: ', err);
        });
    });

    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".tabs").forEach(tabsContainer => {
            const buttons = tabsContainer.querySelectorAll(".tab-btn");
            const contents = tabsContainer.querySelectorAll(".tab-content");

            // Activate first tab if none is active
            const activeBtn = tabsContainer.querySelector(".tab-btn.active") || buttons[0];
            if (activeBtn) activeBtn.classList.add("active");

            const tabId = activeBtn.dataset.tab;
            contents.forEach(content => {
                content.classList.toggle("active", content.id === tabId);
            });

            // Add event listeners
            buttons.forEach(button => {
                button.addEventListener("click", () => {
                    const tabId = button.dataset.tab;

                    buttons.forEach(btn => btn.classList.remove("active"));
                    button.classList.add("active");

                    contents.forEach(content => {
                        content.classList.toggle("active", content.id ===
                            tabId);
                    });
                });
            });
        });
    });

    function sendAjaxRequest(url, method, data, callback) {
        if (!navigator.onLine) {
            callback({
                success: false,
                message: "You are offline. Please check your internet connection."
            });
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 0) {
                    callback({
                        success: false,
                        message: "Request failed. You may be offline or the server is unreachable."
                    });
                } else {
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
            }
        };

        xhr.onerror = function() {
            callback({
                success: false,
                message: "An error occurred during the request. Please check your internet connection."
            });
        };

        xhr.ontimeout = function() {
            callback({
                success: false,
                message: "Request timed out. Please check your internet connection and try again."
            });
        };

        xhr.timeout = 10000;
        xhr.send(data);
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".claim-btn").forEach(button => {
            button.addEventListener("click", () => {
                const referralId = button.getAttribute("data-id");

                if (!referralId) return;

                button.disabled = true;

                sendAjaxRequest("claim_reward.php", "POST", `referral_id=${referralId}`, (
                    response) => {
                    if (response.success) {
                        button.textContent = "Claimed";
                        button.classList.remove("bg-success");
                        button.classList.add("btn-secondary");
                        button.disabled = true;
                        showToasted('Reward Claimed Successfully!', 'success')

                        setTimeout(() => {
                            window.location.href = 'referrals.php';
                        }, 3000);

                    } else {
                        button.disabled = false;
                        button.textContent = "Claim";
                        console.log(response.message || "Failed to claim reward.");
                    }
                });

            }) // Fetch and update rewards
            sendAjaxRequest("get_reward_totals.php", "GET", null, (res) => {
                if (res.success) {
                    const pendingElem = document.getElementById("pendingAmount");
                    const claimedElem = document.getElementById("claimedAmount");

                    if (pendingElem) {
                        pendingElem.textContent = `₦${res.data.pending}`;
                        pendingElem.classList.toggle("text-warning", res.data.pending !==
                            "0.00");
                        pendingElem.classList.toggle("text-dark", res.data.pending === "0.00");
                    }

                    if (claimedElem) {
                        claimedElem.textContent = `₦${res.data.claimed}`;
                        claimedElem.classList.toggle("text-success", res.data.claimed !==
                            "0.00");
                        claimedElem.classList.toggle("text-dark", res.data.claimed === "0.00");
                    }
                }
            });;
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>