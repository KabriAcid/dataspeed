<?php
// about.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - Dataspeed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CDN -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
 <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f3f3f3;
    color: #333;
  }
  .hero {
    background: linear-gradient(to right, #8B0000, #555); /* red to ash */
    color: white;
    padding: 100px 20px;
    text-align: center;
  }
  .hero h1 {
    font-size: 3rem;
    font-weight: bold;
  }
  .section-title {
    text-align: center;
    margin-top: 50px;
    margin-bottom: 30px;
    font-weight: bold;
    color: #8B0000;
  }
  .card {
    border: none;
    background-color: #fafafa;
    transition: all 0.3s ease-in-out;
  }
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
  }
  .card h5 {
    color: #8B0000;
  }
  .footer {
    background-color: #2e2e2e;
    color: #ccc;
    padding: 40px 20px;
    text-align: center;
  }
  .footer a {
    color: #ccc;
    transition: color 0.3s ease;
  }
  .footer a:hover {
    color: #ff4c4c; /* bright red on hover */
  }
</style>

</head>
<body>

<!-- Hero Section -->
<section class="hero">
  <div class="container">
    <h1>About Dataspeed</h1>
    <p class="lead mt-3">Innovating with Code. Empowering with Technology.</p>
  </div>
</section>

<!-- About Content -->
<section class="container mt-5">
  <div class="row">
    <div class="col-md-6 mb-4">
      <h3>Who We Are</h3>
      <p>
        Dataspeed is a modern tech-driven company focused on delivering powerful, scalable, and secure digital solutions.
        We specialize in web development, automation systems, UI/UX design, and enterprise applications. Our mission is
        to empower businesses with intuitive software that drives success.
      </p>
    </div>
    <div class="col-md-6 mb-4">
      <h3>What We Do</h3>
      <ul>
        <li>Fuel Station Management Systems</li>
        <li>Custom Web Applications</li>
        <li>Database & Inventory Solutions</li>
        <li>Responsive Web Design</li>
        <li>API Integrations & Support</li>
      </ul>
    </div>
  </div>
</section>

<!-- Our Team Section -->
<section class="container">
  <h2 class="section-title">Meet the Team</h2>
  <div class="row text-center">
    <div class="col-md-4 mb-4">
      <div class="card p-4">
        <img src="https://img.icons8.com/ios-filled/100/000000/user-male-circle.png" class="mb-3" width="70" alt="Team Member">
        <h5>Kabri Acid</h5>
        <p>Lead Developer & System Architect</p>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card p-4">
        <img src="https://img.icons8.com/ios-filled/100/000000/user-male-circle.png" class="mb-3" width="70" alt="Team Member">
        <h5>Musa Njidda</h5>
        <p>Project Manager</p>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card p-4">
        <img src="https://img.icons8.com/ios-filled/100/000000/user-male-circle.png" class="mb-3" width="70" alt="Team Member">
        <h5>Sadik Kabure</h5>
        <p>UI/UX Designer</p>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<div class="footer">
  <p>&copy; <?php echo date("Y"); ?> Dataspeed. All Rights Reserved.</p>
  <div class="mt-2">
    <a href="https://instagram.com" target="_blank" class="text-light mx-2"><i class="fab fa-instagram"></i></a>
    <a href="https://twitter.com" target="_blank" class="text-light mx-2"><i class="fab fa-twitter"></i></a>
    <a href="https://facebook.com" target="_blank" class="text-light mx-2"><i class="fab fa-facebook"></i></a>
    <a href="mailto:you@example.com" class="text-light mx-2"><i class="fas fa-envelope"></i></a>
  </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
