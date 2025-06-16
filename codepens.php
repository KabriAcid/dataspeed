<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CodePens | Dataspeed</title>
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

    .codepens-header {
      background: linear-gradient(to right, #8B0000, #3a3a3a);
      color: white;
      padding: 60px 20px;
      text-align: center;
    }

    .codepens-header h1 {
      font-size: 2.5rem;
      font-weight: bold;
    }

    .project-card {
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .project-card:hover {
      transform: scale(1.02);
    }

    .project-title {
      background-color: #8B0000;
      color: white;
      padding: 12px;
      font-weight: 600;
    }

    .embed-responsive {
      border-top: 1px solid #ccc;
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
  <div class="codepens-header">
    <h1>Dataspeed CodePens</h1>
    <p>Showcasing our creative front-end snippets and interactive design components.</p>
  </div>

  <!-- CodePen Cards -->
  <div class="container py-5">
    <div class="row">
      <!-- Card 1 -->
      <div class="col-md-6 mb-4">
        <div class="project-card">
          <div class="project-title">Responsive Navbar</div>
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://codepen.io/yourusername/embed/preview/abc123" allowfullscreen></iframe>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-6 mb-4">
        <div class="project-card">
          <div class="project-title">Animated Button Hover</div>
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://codepen.io/yourusername/embed/preview/def456" allowfullscreen></iframe>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-6 mb-4">
        <div class="project-card">
          <div class="project-title">Login Form UI</div>
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://codepen.io/yourusername/embed/preview/ghi789" allowfullscreen></iframe>
          </div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="col-md-6 mb-4">
        <div class="project-card">
          <div class="project-title">CSS Loader Animation</div>
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://codepen.io/yourusername/embed/preview/jkl101" allowfullscreen></iframe>
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
