<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';
require __DIR__ . '/../partials/header.php';

$referrals = getUserReferralDetails($pdo, $user_id);
$rewards = getReferralRewards($pdo, $user_id);
$pendingReferrals = getReferralsByStatus($pdo, $user_id, 'pending');
$completedReferrals = getReferralsByStatus($pdo, $user_id, 'claimed');

?>

<body>
    <main class="container-fluid py-5">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold">Referrals</h5>
                <div class="notification-icon">
                    <a href="notifications.php">
                        <i class="ni ni-bell-55 fs-5 text-gradient text-dark"></i>
                        <span class="notification-badge"></span>
                    </a>
                </div>
            </div>
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
                            <h4 id="pendingAmount" class="amount mb-0 <?= $color; ?> font-weight-bolder">&#8358; <?= number_format($rewards['pending'], 2) ?></h4>
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
                            <h4 id="claimedAmount" class="amount mb-0 <?= $color; ?> font-weight-bolder">&#8358; <?= number_format($rewards['claimed'], 2) ?></h4>
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
                        <table class="table mb-0" style="overflow-x: scroll;">
                            <thead class="table-white">
                                <tr>
                                    <th>Reward</th>
                                    <th>Referee</th>
                                    <th>Status</th>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pendingReferrals)) : ?>
                                    <?php foreach ($pendingReferrals as $referral) : ?>
                                        <?php
                                        $formattedDate = date("M j, Y g:i A", strtotime($referral['created_at']));
                                        ?>
                                        <tr>
                                            <td><strong>&#8358;<?= htmlspecialchars($referral['reward']); ?></strong></td>

                                            <td><?= htmlspecialchars($referral['referee_email']); ?></td>
                                            <td class="text-warning">
                                                <?= htmlspecialchars(ucfirst($referral['status'])); ?></td>
                                            <td><?= $formattedDate; ?></td>
                                            <td>
                                                <button class="border-0 shadow-sm badge bg-success claim-btn"
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
                        <table class="table mb-0">
                            <thead class="">
                                <tr>
                                    <th>Reward</th>
                                    <th>Referee</th>
                                    <th>Status</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($completedReferrals)) : ?>
                                    <?php foreach ($completedReferrals as $referral) : ?>
                                        <?php
                                        $formattedDate = date("M j, Y g:i A", strtotime($referral['created_at']));
                                        ?>
                                        <tr>
                                            <td><strong>&#8358;<?= htmlspecialchars($referral['reward']); ?></strong></td>

                                            <td><?= htmlspecialchars($referral['referee_email']); ?></td>
                                            <td class="text-success"><?= htmlspecialchars(ucfirst($referral['status'])); ?></td>
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

            <div class="row mt-5 px-3">
                <div class="col-lg-6 col-12">
                    <div class="d-flex justify-content-center align-items-center">
                        <!-- Copy Code -->
                        <div class="referral-code shadow-sm px-3 py-2 rounded bg-white position-relative">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="">
                                    <p class="text-xs mb-1 text-secondary font-weight-bold no-wrap">Referral Code</p>
                                    <h4 class="font-weight-bolder primary mb-1 letter-normal fs-6" id="referral_code">
                                        <?= $referrals['referral_code'] ?></h4>
                                </div>
                                <div class="ms-2 ms-md-5">
                                    <svg width="24" id="copy-icon" class="cursor-pointer" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.83333 6.61539C5.12206 6.61539 4.54545 7.18938 4.54545 7.89744V19.1795C4.54545 19.8875 5.12206 20.4615 5.83333 20.4615H14.0758C14.787 20.4615 15.3636 19.8875 15.3636 19.1795V11.3112C15.3636 10.9712 15.2279 10.6451 14.9864 10.4047L11.5571 6.99089C11.3156 6.75046 10.988 6.61539 10.6465 6.61539H5.83333ZM3 7.89744C3 6.33971 4.26853 5.07692 5.83333 5.07692H10.6465C11.3979 5.07692 12.1186 5.37408 12.6499 5.90303L16.0792 9.3168C16.6106 9.84575 16.9091 10.5632 16.9091 11.3112V19.1795C16.9091 20.7372 15.6406 22 14.0758 22H5.83333C4.26853 22 3 20.7372 3 19.1795V7.89744Z" fill="#030D45" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.12121 2.76923C7.12121 2.3444 7.46717 2 7.89394 2H12.1919C12.9434 2 13.664 2.29716 14.1954 2.82611L19.1701 7.77834C19.7015 8.30729 20 9.0247 20 9.77275V17.1282C20 17.553 19.654 17.8974 19.2273 17.8974C18.8005 17.8974 18.4545 17.553 18.4545 17.1282V9.77275C18.4545 9.43273 18.3189 9.10663 18.0773 8.8662L13.1026 3.91397C12.8611 3.67353 12.5335 3.53846 12.1919 3.53846H7.89394C7.46717 3.53846 7.12121 3.19407 7.12121 2.76923Z" fill="#030D45" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Copy link -->
                        <?php
                        $referralLink = $referrals['referral_link'] ?? '';
                        ?>
                        <div class="referral-link rounded mx-3 py-2">
                            <input type="hidden" id="referralLinkInput" value="<?= htmlspecialchars($referralLink) ?>"
                                readonly hidden>
                            <button type="button" id="shareButton" class="btn mb-0 primary-btn py-3 w-100 h-100">Share
                                Link</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>
    <footer class=" my-4">
        <p class="text-xs text-center text-secondary">Copyright &copy; Dreamerscodes 2025. All
            rights reserved.</p>
    </footer>
    <script src="../assets/js/ajax.js"></script>
    <script>
        document.getElementById("shareButton").addEventListener("click", () => {
            let referralLink = document.getElementById("referralLinkInput").value; // Get referral link

            if (navigator.share) {
                navigator.share({
                        title: "Join Now!",
                        text: "Use my referral link to get started:",
                        url: referralLink // Uses the referral link instead of the current URL
                    })
                    .then(() => console.log("Referral link shared successfully!"))
                    .catch(error => console.error("Error sharing:", error));
            } else {
                alert("Web Share API is not supported on this browser.");
            }
        });

        document.getElementById('copy-icon').addEventListener('click', function() {
            const referralCode = document.getElementById('referral_code').innerText.trim();

            // Copy to clipboard
            navigator.clipboard.writeText(referralCode).then(() => {
                const copyBtn = document.getElementById('copy-icon');
                // Change icon to checkmark and color to green

                showToasted('Copied successfully', 'success');

                document.getElementById('copy-icon').innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.9989 11.2858V19.1429C16.9989 20.6735 15.7637 22 14.1168 22H5.88213C4.33813 22 3 20.7756 3 19.1429V7.91841C3 6.3878 4.2352 5.06127 5.88213 5.06127H10.72C11.4405 5.06127 12.1611 5.36739 12.7787 5.8776L16.1755 9.24495C16.6901 9.85719 16.9989 10.5715 16.9989 11.2858Z" fill="#030D45"/>
                <path d="M19.2635 17.9184C18.8517 17.9184 18.44 17.6123 18.44 17.1021V9.75515C18.44 9.44903 18.3371 9.1429 18.0283 8.83678L13.0875 3.93882C12.8816 3.73474 12.4699 3.53066 12.1611 3.53066H7.9408C7.52907 3.6327 7.11733 3.22454 7.11733 2.81637C7.11733 2.40821 7.52907 2.00005 7.9408 2.00005H12.264C12.9845 2.00005 13.7051 2.30617 14.2197 2.81637L19.1605 7.71433C19.6752 8.22454 19.984 8.93882 19.984 9.65311V17.1021C20.0869 17.5103 19.6752 17.9184 19.2635 17.9184Z" fill="#030D45"/>
                </svg>
            `;

                setTimeout(() => {
                    document.getElementById('copy-icon').innerHTML = `
                    <svg width="24" id="copy-icon" class="cursor-pointer" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.83333 6.61539C5.12206 6.61539 4.54545 7.18938 4.54545 7.89744V19.1795C4.54545 19.8875 5.12206 20.4615 5.83333 20.4615H14.0758C14.787 20.4615 15.3636 19.8875 15.3636 19.1795V11.3112C15.3636 10.9712 15.2279 10.6451 14.9864 10.4047L11.5571 6.99089C11.3156 6.75046 10.988 6.61539 10.6465 6.61539H5.83333ZM3 7.89744C3 6.33971 4.26853 5.07692 5.83333 5.07692H10.6465C11.3979 5.07692 12.1186 5.37408 12.6499 5.90303L16.0792 9.3168C16.6106 9.84575 16.9091 10.5632 16.9091 11.3112V19.1795C16.9091 20.7372 15.6406 22 14.0758 22H5.83333C4.26853 22 3 20.7372 3 19.1795V7.89744Z" fill="#030D45"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.12121 2.76923C7.12121 2.3444 7.46717 2 7.89394 2H12.1919C12.9434 2 13.664 2.29716 14.1954 2.82611L19.1701 7.77834C19.7015 8.30729 20 9.0247 20 9.77275V17.1282C20 17.553 19.654 17.8974 19.2273 17.8974C18.8005 17.8974 18.4545 17.553 18.4545 17.1282V9.77275C18.4545 9.43273 18.3189 9.10663 18.0773 8.8662L13.1026 3.91397C12.8611 3.67353 12.5335 3.53846 12.1919 3.53846H7.89394C7.46717 3.53846 7.12121 3.19407 7.12121 2.76923Z" fill="#030D45"/>
                    </svg>
                `;
                }, 2000);

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

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".claim-btn").forEach(button => {
                button.addEventListener("click", () => {
                    const referralId = button.getAttribute("data-id");

                    if (!referralId) return;

                    button.disabled = true;

                    sendAjaxRequest("claim-reward.php", "POST", `referral_id=${referralId}`, (
                        response) => {
                        if (response.success) {
                            button.textContent = "Claimed";
                            button.classList.remove("bg-success");
                            button.classList.add("badge");
                            button.classList.add("bg-secondary");
                            button.disabled = true;
                            showToasted('Reward Claimed Successfully!', 'success')

                            setTimeout(() => {
                                window.location.href = 'referrals.php';
                            }, 3000);

                        } else {
                            button.disabled = false;
                            button.textContent = "Claim";
                            showToasted("Failed to claim reward.", 'error');
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
    <?php require __DIR__ . '/../partials/auth-modal.php'; ?>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>