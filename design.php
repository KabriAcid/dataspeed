<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Design | Dataspeed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap & FontAwesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9f9f9;
      color: #333;
    }

    .design-header {
      background: linear-gradient(to right, #8B0000, #3a3a3a);
      color: white;
      padding: 70px 20px;
      text-align: center;
    }

    .design-header h1 {
      font-size: 2.8rem;
      font-weight: bold;
    }

    .section {
      padding: 50px 20px;
    }

    .section-title {
      color: #8B0000;
      font-weight: 700;
      margin-bottom: 25px;
      text-align: center;
    }

    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      transition: all 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card img {
      border-radius: 10px 10px 0 0;
      height: 200px;
      object-fit: cover;
    }

    .card-body h5 {
      color: #8B0000;
      font-weight: 600;
    }

    .tools ul {
      list-style: none;
      padding-left: 0;
    }

    .tools li {
      background: #eee;
      margin-bottom: 10px;
      padding: 10px 15px;
      border-radius: 6px;
      font-weight: 500;
      color: #333;
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
  <div class="design-header">
    <h1>Dataspeed Design Lab</h1>
    <p>Explore the visuals, systems, and creativity powering Dataspeed.</p>
  </div>

  <!-- Section: Design Philosophy -->
  <div class="section">
    <h2 class="section-title">Design Philosophy</h2>
    <div class="container">
      <p class="lead text-center">
        At Dataspeed, UI/UX is not just how it looks, but how it works. <br>
        Sadik Kabure and Kabri Acid ensure every screen delivers clarity, simplicity, and joy.
      </p>
    </div>
  </div>

  <!-- Section: Design Tools -->
  <div class="section bg-light tools">
    <h2 class="section-title">Tools We Use</h2>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <ul>
            <li><i class="fas fa-pen-nib text-danger"></i> Figma</li>
            <li><i class="fas fa-layer-group text-danger"></i> Adobe XD</li>
            <li><i class="fas fa-bezier-curve text-danger"></i> Illustrator</li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul>
            <li><i class="fas fa-code text-danger"></i> HTML, CSS, JS</li>
            <li><i class="fas fa-laptop-code text-danger"></i> Bootstrap + Tailwind</li>
            <li><i class="fas fa-palette text-danger"></i> UI Kits & Components</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Section: Showcase Projects -->
  <div class="section">
    <h2 class="section-title">Design Showcase</h2>
    <div class="container">
      <div class="row">

        <!-- Project 1 -->
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="public/assets/img/design/ui-1.jpg" alt="UI Design 1">
            <div class="card-body">
              <h5>Minimal Dashboard UI</h5>
              <p>A clean, responsive dashboard designed with Figma and Tailwind for quick user navigation.</p>
            </div>
          </div>
        </div>

        <!-- Project 2 -->
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="public/assets/img/design/mobile-app.jpg" alt="Mobile App">
            <div class="card-body">
              <h5>Mobile App Interface</h5>
              <p>Modern UI for mobile banking — built around usability and touch-first gestures.</p>
            </div>
          </div>
        </div>

        <!-- Project 3 -->
        <div class="col-md-4 mb-4">
          <div class="card">
            <img src="public/assets/img/design/webshop.jpg" alt="Web Shop">
            <div class="card-body">
              <h5>E-commerce Design</h5>
              <p>High-conversion product layout created for Dataspeed clients using modern UI best practices.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; 2025 Dataspeed. All rights reserved.</p>
    <p>
      <a href="https://x.com/dataspeed" target="_blank">X</a> |
      <a href="https://instagram.com/dataspeed" target="_blank">Instagram</a> |
      <a href="mailto:info@dataspeed.com">Email</a>
    </p>
  </div>

</body>
</html>
