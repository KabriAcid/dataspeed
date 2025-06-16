<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Documentation | Dataspeed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & FontAwesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
      color: #333;
    }

    .doc-header {
      background: linear-gradient(to right, #8B0000, #3a3a3a);
      color: #fff;
      padding: 60px 20px;
      text-align: center;
    }

    .doc-header h1 {
      font-size: 2.8rem;
      font-weight: bold;
    }

    .doc-section {
      padding: 50px 20px;
    }

    .doc-section h2 {
      color: #8B0000;
      margin-bottom: 25px;
      font-weight: bold;
      font-size: 1.8rem;
    }

    .doc-section p {
      line-height: 1.7;
    }

    .code-box {
      background: #333;
      color: #0f0;
      padding: 15px;
      border-radius: 6px;
      font-family: 'Courier New', monospace;
      font-size: 14px;
      margin: 15px 0;
      overflow-x: auto;
    }

    .footer {
      background-color: #2e2e2e;
      color: #ccc;
      text-align: center;
      padding: 30px 10px;
    }

    .footer a {
      color: #ccc;
      margin: 0 8px;
    }

    .footer a:hover {
      color: #ff4c4c;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="doc-header">
    <h1>Dataspeed Documentation</h1>
    <p>Everything you need to get started and understand our system.</p>
  </div>

  <!-- Section: Overview -->
  <div class="doc-section">
    <div class="container">
      <h2>1. Project Overview</h2>
      <p>
        Dataspeed is a modern, lightweight platform designed by Kabri Acid, Sadik Kabure and Musa Njidda offering streamlined user interactions through clean UI/UX and robust backend logic. It supports everything from user authentication to content management and fuel station extension modules.
      </p>
    </div>
  </div>

  <!-- Section: Installation -->
  <div class="doc-section bg-light">
    <div class="container">
      <h2>2. Installation Guide</h2>
      <p>Follow the steps below to set up Dataspeed on your local machine:</p>
      <div class="code-box">
        git clone https://github.com/KabriAcid/dataspeed.git <br>
        cd dataspeed <br>
        php -S localhost:8000 <br>
        # Or setup with XAMPP and import SQL database
      </div>
    </div>
  </div>

  <!-- Section: Features -->
  <div class="doc-section">
    <div class="container">
      <h2>3. Key Features</h2>
      <ul>
        <li>✅ Clean and responsive UI with red-ash branding</li>
        <li>✅ User authentication system</li>
        <li>✅ Admin dashboard for managing content</li>
        <li>✅ Integrated design showcase</li>
        <li>✅ Modular pages like Blog, About, Contact</li>
      </ul>
    </div>
  </div>

  <!-- Section: How to Use -->
  <div class="doc-section bg-light">
    <div class="container">
      <h2>4. How to Use</h2>
      <p>Each page is modular. To edit, go to the relevant file in `/pages/` or `/public/`. Sample edit flow:</p>
      <div class="code-box">
        <!-- Edit home.php -->
        nano pages/home.php <br>
        <!-- Edit contact form handler -->
        nano includes/contact-process.php
      </div>
    </div>
  </div>

  <!-- Section: Support -->
  <div class="doc-section">
    <div class="container">
      <h2>5. Support & Contact</h2>
      <p>If you encounter any issues or have feature requests, reach out to:</p>
      <ul>
        <li><strong>Email:</strong> <a href="mailto:sadik@dataspeed.com">sadik@dataspeed.com</a></li>
        <li><strong>Telegram:</strong> <a href="https://t.me/dataspeed">t.me/dataspeed</a></li>
        <li><strong>X (Twitter):</strong> <a href="https://x.com/dataspeed">@dataspeed</a></li>
      </ul>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; 2025 Dataspeed. Designed by Sadik Kabure and Kabri Acid.</p>
  </div>

</body>
</html>
