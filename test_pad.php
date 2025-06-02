<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dataspeed - Homepage</title>
    <link rel="shortcut icon" href="public/logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
      .plan-card {
        cursor: pointer;
        border: 2px solid #f0f0f0;
        transition: box-shadow .2s, border-color .2s;
      }
      .plan-card.active, .plan-card:active, .plan-card:focus, .plan-card:hover {
        border-color: #007bff !important;
        box-shadow: 0 0 0 2px #007bff;
        background: #eaf3ff;
      }
      @media (max-width: 575.98px) {
        .modal-content { max-width: 98vw; }
      }
    </style>
</head>
<body>
<?php
$plans = [
  ["price" => "₦370", "volume" => "500MB", "duration" => "1 DAY"],
  ["price" => "₦500", "volume" => "1GB", "duration" => "1 DAY"],
  ["price" => "₦500", "volume" => "500MB", "duration" => "7 DAYS"],
  ["price" => "₦800", "volume" => "1.2GB", "duration" => "7 DAYS"],
  ["price" => "₦860", "volume" => "2.5GB", "duration" => "1 DAY"],
  ["price" => "₦950", "volume" => "2.5GB", "duration" => "1 DAY"],
  ["price" => "₦1,160", "volume" => "3.2GB"],
  ["price" => "₦2,730", "volume" => "6GB"]
];
?>

<!-- Service Plans Modal -->
<div id="servicePlansModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Choose a Service Plan</h5>
      <button class="close-btn" id="closeServicePlans" style="font-size:1.5rem;">&times;</button>
    </div>
    <div class="modal-body" id="plansContainer">
      <div class="container-fluid px-0">
        <div class="row g-2">
          <?php foreach ($plans as $idx => $plan): ?>
            <div class="col-6">
              <div class="card mb-2 plan-card" tabindex="0"
                data-idx="<?= $idx ?>"
                data-price="<?= htmlspecialchars($plan['price']) ?>"
                data-volume="<?= htmlspecialchars($plan['volume']) ?>"
                data-duration="<?= htmlspecialchars($plan['duration'] ?? 'N/A') ?>">
                <div class="card-body text-center">
                  <div class="fw-bold fs-5 text-primary"><?= htmlspecialchars($plan['price']) ?></div>
                  <div class="fs-6"><?= htmlspecialchars($plan['volume']) ?></div>
                  <div class="text-muted"><?= htmlspecialchars($plan['duration'] ?? 'N/A') ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Trigger button -->
<button id="openServicePlansBtn" class="btn primary-btn">Open Plans</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const servicePlansModal = document.getElementById('servicePlansModal');
  const closeServicePlans = document.getElementById('closeServicePlans');
  const openServicePlansBtn = document.getElementById('openServicePlansBtn');
  const plansContainer = document.getElementById('plansContainer');
  let selectedPlan = null;

  openServicePlansBtn.addEventListener('click', () => {
    servicePlansModal.style.display = 'block';
    // Remove previous selection highlight
    plansContainer.querySelectorAll('.plan-card').forEach(c => c.classList.remove('active'));
    selectedPlan = null;
  });

  closeServicePlans.addEventListener('click', () => {
    servicePlansModal.style.display = 'none';
  });

  servicePlansModal.addEventListener('click', (e) => {
    if (e.target === servicePlansModal) {
      servicePlansModal.style.display = 'none';
    }
  });

  plansContainer.addEventListener('click', function(e) {
    let card = e.target.closest('.plan-card');
    if (!card) return;
    // Remove active from all
    plansContainer.querySelectorAll('.plan-card').forEach(c => c.classList.remove('active'));
    card.classList.add('active');
    selectedPlan = {
      price: card.getAttribute('data-price'),
      volume: card.getAttribute('data-volume'),
      duration: card.getAttribute('data-duration')
    };
    // Modal disappears after selection
    setTimeout(() => {
      servicePlansModal.style.display = 'none';
      // Use selectedPlan for AJAX or further processing here
      alert(`Selected: ${selectedPlan.volume} for ${selectedPlan.price} (${selectedPlan.duration})`);
    }, 150);
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>