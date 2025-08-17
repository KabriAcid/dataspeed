<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us | Dataspeed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 4.5 CSS -->
   <link rel="shortcut icon" href="public/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="public/assets/css/style.css">
  <style>
    body {
      background-color: #f2f2f2;
      font-family: 'Segoe UI', sans-serif;
    }

    .contact-hero {
      background: linear-gradient(to right, #8B0000, #444);
      color: white;
      padding: 80px 20px;
      text-align: center;
    }

    .contact-hero h1 {
      font-weight: bold;
      font-size: 2.8rem;
    }

    .contact-section {
      padding: 50px 15px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .form-control {
      border-radius: 8px;
    }

    .btn-red {
      background-color: #8B0000;
      color: white;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .btn-red:hover {
      background-color: #a60000;
    }

    .footer {
      background-color: #2e2e2e;
      color: #ccc;
      padding: 40px 20px;
      text-align: center;
    }

    .footer a {
      color: #ccc;
      transition: 0.3s;
    }

    .footer a:hover {
      color: #ff4c4c;
    }

    .footer i {
      font-size: 1.2rem;
      margin: 0 8px;
    }
  </style>
</head>
<body>

  <!-- Hero Section -->
  <section class="contact-hero">
    <h1>Contact Dataspeed</h1>
    <p class="lead">Let’s connect — we’d love to hear from you.</p>
  </section>

  <!-- Contact Form Section -->
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="contact-section">
          <form action="" method="POST">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" name="name" id="name" required>
              </div>
              <div class="form-group col-md-6">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" name="email" id="email" required>
              </div>
            </div>
            <div class="form-group">
              <label for="subject">Subject</label>
              <input type="text" class="form-control" name="subject" id="subject">
            </div>
            <div class="form-group">
              <label for="message">Message</label>
              <textarea class="form-control" name="message" id="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-red btn-block">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>Follow Dataspeed on:
      <a href="https://x.com/dataspeed" target="_blank"><i class="fab fa-x-twitter"></i></a>
      <a href="https://instagram.com/dataspeed" target="_blank"><i class="fab fa-instagram"></i></a>
      <a href="https://facebook.com/dataspeed" target="_blank"><i class="fab fa-facebook"></i></a>
      <a href="https://t.me/dataspeed" target="_blank"><i class="fab fa-telegram"></i></a>
      <a href="mailto:info@dataspeed.com"><i class="fas fa-envelope"></i></a>
    </p>
    <p>&copy; 2025 Dataspeed. All rights reserved.</p>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
