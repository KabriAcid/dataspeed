<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Blog | Dataspeed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap + FontAwesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      color: #333;
    }

    .blog-header {
      background: linear-gradient(to right, #8B0000, #3a3a3a);
      color: white;
      padding: 70px 20px;
      text-align: center;
    }

    .blog-header h1 {
      font-weight: 700;
      font-size: 2.8rem;
    }

    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      transition: 0.3s;
    }

    .card:hover {
      transform: scale(1.02);
    }

    .card img {
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
      height: 200px;
      object-fit: cover;
    }

    .card-body h5 {
      color: #8B0000;
      font-weight: 600;
    }

    .card-body p {
      font-size: 0.95rem;
      color: #555;
    }

    .read-more {
      color: #8B0000;
      text-decoration: none;
      font-weight: 500;
    }

    .read-more:hover {
      text-decoration: underline;
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
  <div class="blog-header">
    <h1>Dataspeed Blog</h1>
    <p class="lead">Latest updates, design stories, and tech insights from our team</p>
  </div>

  <!-- Blog Posts -->
  <div class="container my-5">
    <div class="row">

      <!-- Blog Card 1 -->
      <div class="col-md-4 mb-4">
        <div class="card">
          <img src="public/assets/img/blog/uiux.jpg" alt="UI/UX Design">
          <div class="card-body">
            <h5>Designing with Emotion: The Dataspeed UX Process</h5>
            <p>Sadik Kabure shares how emotion-driven design shapes Dataspeed’s user experience.</p>
            <a href="#" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Blog Card 2 -->
      <div class="col-md-4 mb-4">
        <div class="card">
          <img src="public/assets/img/blog/devtools.jpg" alt="Dev Tools">
          <div class="card-body">
            <h5>Top 5 Tools We Use to Build Dataspeed</h5>
            <p>From Figma to PHPStorm — see the tools behind our clean UI and smart backend.</p>
            <a href="#" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Blog Card 3 -->
      <div class="col-md-4 mb-4">
        <div class="card">
          <img src="public/assets/img/blog/launch.jpg" alt="Launch">
          <div class="card-body">
            <h5>Preparing for the Official Launch of Dataspeed</h5>
            <p>Kabri Acid walks through the final polish before the platform goes live.</p>
            <a href="#" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
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
      <a href="https://facebook.com/dataspeed" target="_blank">Facebook</a> |
      <a href="mailto:info@dataspeed.com">Email</a>
    </p>
  </div>

</body>
</html>
