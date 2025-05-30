<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Buy Data</h5>
        </header>

        <div class="network-section">
            <div class="network-tabs">
                <div class="network-tab" id="mtn-tab" data-network="mtn" style="--brand-color: #ffcc00;">
                    <img src="../../assets/icons/mtn_logo.svg" alt="mtn-logo">
                    <span>MTN</span>
                </div>
                <div class="network-tab" id="airtel-tab" data-network="airtel" style="--brand-color: #EB1922;">
                    <img src="../../assets/icons/airtel-logo-1.svg" alt="airtel-logo">
                    <span>Airtel</span>
                </div>
                <div class="network-tab" id="glo-tab" data-network="glo" style="--brand-color: #50B651;">
                    <img src="../../assets/icons/glo_logo.svg" alt="glo-logo">
                    <span>Glo</span>
                </div>
                <div class="network-tab" id="9mobile-tab" data-network="9mobile" style="--brand-color: #D6E806;">
                    <img src="../../assets/icons/9mobile_logo.svg" alt="9mobile-logo">
                    <span>9Mobile</span>
                </div>
            </div>
        </div>

        <div class="plans-section">
            <div class="d-flex justify-content-between">
                <!-- Card  -->
                <div>
                    <div class="card p-0 plan-card border-0 mb-3 shadow">
                        <div class="card-body px-3 py-2 text-dark rounded-3">
                            <div class="text-center">
                                <p id="data-amount">&#8358;800</p>
                                <h5 id="data-volume">1.2GB</h5>
                                <p id="data-duration">7 DAYS</p>
                            </div>
                            <!-- <span class="badge bg-dark-subtle text-dark fw-semibold">AWOOF</span> -->
                        </div>
                    </div>
                </div>
                <!-- Card  -->
                <div>
                    <div class="card p-0 plan-card border-0 mb-3 shadow">
                        <div class="card-body px-3 py-2 text-dark rounded-3">
                            <div class="text-center">
                                <p id="data-amount">&#8358;800</p>
                                <h5 id="data-volume">1.2GB</h5>
                                <p id="data-duration">7 DAYS</p>
                            </div>
                            <!-- <span class="badge bg-dark-subtle text-dark fw-semibold">AWOOF</span> -->
                        </div>
                    </div>
                </div>
                <!-- Card  -->
                <div>
                    <div class="card p-0 plan-card border-0 mb-3 shadow">
                        <div class="card-body px-3 py-2 text-dark rounded-3">
                            <div class="text-center">
                                <p id="data-amount">&#8358;800</p>
                                <h5 id="data-volume">1.2GB</h5>
                                <p id="data-duration">7 DAYS</p>
                            </div>
                            <!-- <span class="badge bg-dark-subtle text-dark fw-semibold">AWOOF</span> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <form action="" method="post">
            <input type="number" name="phone_number" class="input" placeholder="Phone Number" maxlength="10">
            <!-- Trigger Button -->
            <button type="button" class="btn w-100 mt-3 primary-btn" onclick="openModal()">Purchase</button>
        </form>

        <!-- <div class="see-all" onclick="openModal()">SEE ALL</div> -->


        <!-- Bottom navigation -->
        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>
    </main>
<!-- Modal Structure -->
<div id="confirmModal" class="modal-overlay">
  <div class="modal-content">

    <!-- Header -->
    <div class="modal-header">
      <span class="modal-title">Confirm</span>
      <button class="close-btn" onclick="closeModal()">×</button>
    </div>

    <!-- Body -->
    <div class="modal-body">
      <p class="subtitle">Send to</p>
      <h2 class="phone-number">0906 789 5453</h2>

      <div class="info-row">
        <span>Product</span>
        <span><i class="icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 19.51L12.01 19.4989M2 8C8 3.5 16 3.5 22 8M5 12C9 8.99999 15 9 19 12M8.5 15.5C10.7504 14.1 13.2498 14.0996 15.5001 15.5"
                                stroke="#94241E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
        </i> Internet Data</span>
      </div>
      <div class="info-row">
        <span>Amount</span>
        <span>₦370.00</span>
      </div>
      <div class="info-row">
        <span>Data Package</span>
        <span>500 MB</span>
      </div>
      <div class="info-row">
        <span>Provider</span>
        <span>MTN AWOOF</span>
      </div>
    </div>

    <!-- Footer -->
    <div class="modal-footer">
      <button class="pay-btn" onclick="submitPayment()">Pay</button>
    </div>
  </div>
</div>


    </div>
  </div>
</div>

<script>
    document.querySelectorAll('.network-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        // Remove active state from all tabs
        document.querySelectorAll('.network-tab').forEach(t => {
            t.classList.remove('active');
            t.style.backgroundColor = ''; // Reset color
        });

        // Add active state to the clicked tab
        this.classList.add('active');
        const brandColor = this.style.getPropertyValue('--brand-color');
        this.style.backgroundColor = brandColor;

        // if active tab is airtel then change the svg icon to airtel
        if (this.id === 'airtel-tab') {
            // Change only the image source for Airtel
            document.querySelector('.network-tabs img').src = '../../assets/icons/airtel-logo-2.svg';
        }
    });
});

function openModal() {
  document.getElementById('confirmModal').style.display = 'flex';
}

function closeModal() {
  document.getElementById('confirmModal').style.display = 'none';
}

function submitPayment() {
  closeModal();
}
</script>

    <script src="../../assets/js/ajax.js"></script>
    <!-- FontAwesome CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>