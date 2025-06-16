<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Figma Designs | Dataspeed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Font Awesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
      color: #333;
    }

    .figma-header {
      background: linear-gradient(to right, #8B0000, #3a3a3a);
      color: white;
      padding: 60px 20px;
      text-align: center;
    }

    .figma-header h1 {
      font-size: 2.5rem;
      font-weight: bold;
    }

    .figma-header p {
      font-size: 1.1rem;
      margin-top: 10px;
    }

    .figma-card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      margin-bottom: 30px;
      transition: transform 0.3s ease;
    }

    .figma-card:hover {
      transform: translateY(-5px);
    }

    .figma-card iframe {
      width: 100%;
      height: 400px;
      border: none;
    }

    .figma-card .card-body {
      padding: 20px;
    }

    .figma-card .card-title {
      font-weight: bold;
      color: #8B0000;
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
  <div class="figma-header">
    <h1>Figma UI Designs</h1>
    <p>Prototypes and design assets by Sadik Kabure & Kabri Acid</p>
  </div>

  <!-- Figma Cards -->
  <div class="container py-5">
    <div class="row">
      <!-- Figma Card 1 -->
      <div class="col-md-6">
        <div class="figma-card">
          <iframe src="https://www.figma.com/embed?embed_host=share&url=https://www.figma.com/file/ExampleLink1" allowfullscreen></iframe>
          <div class="card-body">
            <h5 class="card-title">Dataspeed Homepage Redesign</h5>
            <p class="card-text">A clean and responsive homepage layout with modern CTA sections and red-ash branding.</p>
          </div>
        </div>
      </div>

      <!-- Figma Card 2 -->
      <div class="col-md-6">
        <div class="figma-card">
          <iframe src="https://www.figma.com/embed?embed_host=share&url=https://www.figma.com/file/ExampleLink2" allowfullscreen></iframe>
          <div class="card-body">
            <h5 class="card-title">Admin Dashboard UI</h5>
            <p class="card-text">Prototype for managing blog posts, contact messages, and user stats using Figma auto-layouts.</p>
          </div>
        </div>
      </div>

      <!-- Figma Card 3 -->
      <div class="col-md-6">
        <div class="figma-card">
          <iframe src="https://www.figma.com/embed?embed_host=share&url=https://www.figma.com/file/ExampleLink3" allowfullscreen></iframe>
          <div class="card-body">
            <h5 class="card-title">Mobile App Interface</h5>
            <p class="card-text">Dataspeed mobile UI prototype for on-the-go fuel station and content interaction.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; 2025 Dataspeed. Designed by Sadik Kabure & Kabri Acid.</p>
  </div>

</body>
</html>
