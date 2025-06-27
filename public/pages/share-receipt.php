<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

// Get transaction reference from GET
$reference = $_GET['ref'] ?? null;
if (!$reference) {
    echo "<h2 class='text-center mt-5'>No receipt reference provided.</h2>";
    exit;
}

// Fetch transaction by reference
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE reference = ? LIMIT 1");
$stmt->execute([$reference]);
$txn = $stmt->fetch();

if (!$txn) {
    echo "<h2 class='text-center mt-5'>No receipt found for this reference.</h2>";
    exit;
}

// Format values
$amount = "₦" . number_format($txn['amount'], 2);
$refNumber = htmlspecialchars($txn['reference']);
$date = date("d M Y, H:i", strtotime($txn['created_at']));
$method = $txn['type'] ?? 'N/A';
$sender = $txn['email'] ?? 'N/A';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment Success - Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .receipt-container {
            border-radius: 1.5rem;
            padding: 2.5rem 2rem;
            max-width: 600px;
            text-wrap: wrap;
            width: 100%;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #48bb78;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto -1.5rem;
            font-size: 2.5rem;
            box-shadow: 0 8px 20px rgba(72, 187, 120, 0.3);
        }

        .divider {
            height: 1px;
            background: #4a5568;
            margin: 2rem 0;
        }

        .amount-value {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .download-btn {
            background-color: #94241E !important;
            color: #fff;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            padding: 10px;
            transition: all 0.3s;
            background: transparent;
        }

        .download-btn:hover {
            background: #232b3b;
        }

        /* .perforated-edge {
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 0 0 1.5rem 1.5rem;
        } */
    </style>
</head>

<body>
    <div id="receipt-section" class="receipt-container">
        <div class="success-icon mb-3">
            <span class="fw-bold text-white">&#10003;</span>
        </div>
        <h1 class="text-center fw-bold mb-2">Payment Success!</h1>
        <p class="text-center text-secondary mb-4">Your payment has been successfully done.</p>
        <div class="divider"></div>
        <div class="text-center mb-4">
            <div class="text-secondary">Total Payment</div>
            <div class="amount-value"><?= $amount ?></div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="bg-secondary bg-opacity-25 rounded p-3">
                    <div class="small text-secondary">Ref Number</div>
                    <div class="fw-semibold"><?= $refNumber ?></div>
                </div>
            </div>
            <div class="col-6">
                <div class="bg-secondary bg-opacity-25 rounded p-3">
                    <div class="small text-secondary">Payment Time</div>
                    <div class="fw-semibold"><?= $date ?></div>
                </div>
            </div>
            <div class="col-6">
                <div class="bg-secondary bg-opacity-25 rounded p-3">
                    <div class="small text-secondary">Payment Method</div>
                    <div class="fw-semibold"><?= htmlspecialchars($method) ?></div>
                </div>
            </div>
            <div class="col-6">
                <div class="bg-secondary bg-opacity-25 rounded p-3">
                    <div class="small text-secondary">Sender Name</div>
                    <div class="fw-semibold"><?= htmlspecialchars($sender) ?></div>
                </div>
            </div>
        </div>
        <button id="download-receipt" class="download-btn w-100 mb-2">
            <svg class="me-2" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" />
            </svg>
            Download as PNG
        </button>
        <div class="perforated-edge"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        document.getElementById('download-receipt').addEventListener('click', function() {
            html2canvas(document.getElementById('receipt-section')).then(function(canvas) {
                const link = document.createElement('a');
                link.download = 'receipt-<?= $refNumber ?>.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        });
    </script>
</body>

</html>