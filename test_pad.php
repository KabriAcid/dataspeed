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
    <!-- <link rel="stylesheet" href="public/assets/css/style.css"> -->
</head>
<body>
<!-- MTN Awoof Modal -->
<div class="modal fade" id="mtnAwoofModal" tabindex="-1" aria-labelledby="mtnAwoofModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title fw-bold text-warning" id="mtnAwoofModalLabel">MTN Awoof</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <div class="container">
          <div class="row g-3">
            <!-- Dynamically Loop Through Plans -->
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

            foreach ($plans as $plan) {
              echo "
              <div class='col-12 col-md-6'>
                <div class='card shadow-sm border-0'>
                  <div class='card-body text-center'>
                    <h5 class='card-title fw-bold text-primary'>{$plan['price']}</h5>
                    <p class='card-text text-dark'>{$plan['volume']}</p>
                    <p class='card-text text-muted'>" . ($plan['duration'] ?? 'N/A') . "</p>
                  </div>
                </div>
              </div>";
            }
            ?>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>