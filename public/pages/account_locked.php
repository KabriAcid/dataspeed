<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/header.php';

if (!isset($_SESSION['locked_user_id'])) {
    header("Location: login.php");
    exit;
}

$locked_user_id = $_SESSION['locked_user_id'] ?? null;

// Fetch support contact details from settings table
$settings = [];
try {
    $stmt = $pdo->query("SELECT `key`, `value` FROM settings");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $settings[$row['key']] = $row['value'];
    }
} catch (Throwable $e) {
    $settings = [];
}

$supportEmail = $settings['support_email'] ?? '';
$supportPhone = $settings['support_phone'] ?? '';

// Predefined reasons for account lock
$reasons = [
    "Forgot PIN",
    "Multiple failed PIN attempts",
    "Suspicious activity detected",
    "Account locked by mistake",
    "Multiple failed login attempts",
    "Other"
];

// Check if the user has already submitted a complaint
$stmt = $pdo->prepare("SELECT COUNT(*) FROM account_complaints WHERE status = 'pending' AND user_id = ?");
$stmt->execute([$locked_user_id]);
$complaintExists = $stmt->fetchColumn() > 0;
?>

<body>
    <main class="container py-4 account-locked-page">
        <?php if ($complaintExists): ?>
            <div class="success-message">
                <div class="d-flex justify-content-between mb-5 align-items-center">
                    <a href="login.php" class="p-0 m-0">
                        <svg width="20" height="19" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.60564 1.65147L3.73182 6.5253H16V8.47483H3.73182L8.60564 13.3487L7.22712 14.7272L0 7.50006L7.22712 0.272949L8.60564 1.65147Z"
                                fill="#722F37" />
                        </svg>
                    </a>
                    <h3 class="primary p-0 m-0">Complaint Submitted</h3>
                    <h2></h2>
                </div>

                <p>Your complaint has been submitted successfully. Our team will review it and get back to you shortly.</p>
                <p>Steps to reset your account:</p>
                <ol>
                    <li class="mb-2">Wait for an email or SMS from our support team.</li>
                    <li class="mb-2">Follow the instructions provided to reset your account.</li>
                    <li class="mb-2">If you need further assistance, contact our support team.</li>
                </ol>
                <div class="d-flex justify-content-center mt-4">
                    <button id="resend_email" class="outline-btn">Resend Email</button>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-5">
                <a href="#" id="contact_us" class="fw-bold">Contact Us Here</a>
            </div>
        <?php else: ?>
            <?php if (!isset($_GET['submitted']) || $_GET['submitted'] !== 'true'): ?>
                <header>
                    <h3 class="text-center">Account Locked</h3>
                    <p class="text-center">Your account has been locked due to multiple failed attempts. Please submit a complaint to unlock your account.</p>
                </header>
            <?php endif; ?>

            <!-- Show the complaint form -->
            <form id="accountLockForm" method="POST" class="form-container animate-fade-in">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($locked_user_id); ?>">

                <div class="form-group">
                    <label for="reason">Reason for Account Lock</label>
                    <select name="reason" id="reason" class="input">
                        <option value="">-- Select a Reason --</option>
                        <?php foreach ($reasons as $reason): ?>
                            <option value="<?php echo htmlspecialchars($reason); ?>"><?php echo htmlspecialchars($reason); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn primary-btn" id="submit_complain">Submit Complaint</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Contact Modal -->
        <div id="contactModal" class="modal-backdrop" style="display:none;">
            <div class="modal-content-box">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="m-0">Contact Support</h6>
                    <button type="button" id="closeContactModal" class="btn btn-sm btn-light">Close</button>
                </div>
                <div>
                    <p class="mb-2"><strong>Email:</strong> <?php if ($supportEmail): ?><a href="mailto:<?php echo htmlspecialchars($supportEmail); ?>"><?php echo htmlspecialchars($supportEmail); ?></a><?php endif; ?></p>
                    <p class="mb-2"><strong>Phone:</strong> <?php if ($supportPhone): ?><a href="tel:<?php echo htmlspecialchars($supportPhone); ?>"><?php echo htmlspecialchars($supportPhone); ?></a><?php endif; ?></p>
                    <p class="mb-2"><strong>WhatsApp:</strong> <?php if ($supportPhone): ?><a href="https://wa.me/<?php echo htmlspecialchars($supportPhone); ?>?text=Hello%20DataSpeed%20Support%2C%20my%20account%20is%20locked." target="_blank" rel="noopener">Chat on WhatsApp</a><?php endif; ?></p>
                </div>
            </div>
        </div>

        <!-- Overlay -->
        <div id="bodyOverlay" class="body-overlay" style="display: none;">
            <div class="overlay-spinner"></div>
        </div>

        <!-- Copyright text -->
        <script src="../assets/js/toggle-password.js"></script>
    </main>
    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/account-locked.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const contactBtn = document.getElementById('contact_us');
            const contactModal = document.getElementById('contactModal');
            const closeModal = document.getElementById('closeContactModal');
            const resendBtn = document.getElementById('resend_email');
            if (contactBtn && contactModal) {
                contactBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    contactModal.style.display = 'flex';
                });
            }
            if (closeModal) {
                closeModal.addEventListener('click', () => contactModal.style.display = 'none');
            }
            if (contactModal) {
                contactModal.addEventListener('click', (e) => {
                    if (e.target === contactModal) contactModal.style.display = 'none';
                });
            }

            // Light client-side cooldown after a successful resend (listens to toasted success)
            if (resendBtn) {
                const originalText = resendBtn.textContent;
                const startCooldown = (secs = 60) => {
                    let remaining = secs;
                    resendBtn.disabled = true;
                    const tick = () => {
                        resendBtn.textContent = `Resend Email (${remaining}s)`;
                        remaining--;
                        if (remaining < 0) {
                            resendBtn.textContent = originalText;
                            resendBtn.disabled = false;
                        } else {
                            setTimeout(tick, 1000);
                        }
                    };
                    tick();
                };

                // Hook into global showToasted if present to detect success messages
                const maybeWrapShowToasted = () => {
                    if (typeof window.showToasted === 'function' && !window.__wrappedToastedCooldown) {
                        const orig = window.showToasted;
                        window.showToasted = function(message, type) {
                            try {
                                if (type === 'success' && /email sent successfully|resent|check your inbox/i.test(String(message))) {
                                    startCooldown(60);
                                }
                            } catch (_) {
                                /* noop */
                            }
                            return orig.apply(this, arguments);
                        };
                        window.__wrappedToastedCooldown = true;
                    }
                };
                // Attempt immediate wrap and again shortly in case scripts load late
                maybeWrapShowToasted();
                setTimeout(maybeWrapShowToasted, 500);
            }
        });
    </script>
</body>

</html>