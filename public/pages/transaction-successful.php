<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Transaction Successful</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f4f9f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background: #ffffff;
      padding: 2rem;
      border-radius: 1rem;
      text-align: center;
      max-width: 600px;
      width: 100%;
    }

    .container img {
      width: 160px;
      height: auto;
      margin-bottom: 1rem;
    }

    h1 {
      color: #27ae60;
      margin-bottom: 0.5rem;
    }

    p {
      color: #555;
      margin-top: 0;
    }

    .details {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      margin-top: 2rem;
      font-size: 0.95rem;
      border-top: 1px solid #ddd;
      padding-top: 1rem;
    }

    .detail-item {
      width: 48%;
      margin-bottom: 1rem;
    }

    .label {
      font-weight: bold;
      color: #333;
    }

    .value {
      color: #444;
    }

    @media (max-width: 480px) {
      .detail-item {
        width: 100%;
      }
    }
  </style>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>
<body>
  <div class="container">
    <!-- Include LottieFiles player -->

    <lottie-player
        src="../assets/gif/Lottie-Animation.json"
        background="transparent"
        speed="1"
        style="width: 180px; height: 180px;"
        autoplay
    >
    </lottie-player>

    <h1>Transaction Successful</h1>
    <p>Your payment was processed successfully.</p>

    <div class="details">
      <div class="detail-item">
        <div class="label">Transaction ID</div>
        <div class="value">TXN-99338477</div>
      </div>
      <div class="detail-item">
        <div class="label">Date</div>
        <div class="value">June 10, 2025</div>
      </div>
      <div class="detail-item">
        <div class="label">Amount</div>
        <div class="value">₦5,000.00</div>
      </div>
      <div class="detail-item">
        <div class="label">Recipient</div>
        <div class="value">Airtime (MTN)</div>
      </div>
      <div class="detail-item">
        <div class="label">Status</div>
        <div class="value" style="color: green;">Successful</div>
      </div>
    </div>
  </div>
</body>
</html>
