<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>DragonStone | Eco-Friendly Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f6f9f6;
      font-family: 'Segoe UI', Arial, sans-serif;
    }
    header.hero {
      background: #3c6e47 url('assets/images/hero.jpg') center/cover no-repeat;
      color: white;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
      min-height: 400px;
    }
    .category-card img {
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
    }
    .category-card {
      transition: all 0.3s ease;
    }
    .category-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    footer {
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <!-- Hero Section -->
  <header class="hero text-center d-flex align-items-center justify-content-center mb-5">
    <div class="container py-5">
      <h1 class="display-4 fw-bold">Welcome to DragonStone</h1>
      <p class="lead mb-4">Eco-friendly. Practical. Beautiful. Responsible living starts here.</p>
      <a class="btn btn-light btn-lg shadow-sm" href="products.php">Shop Now</a>
    </div>
  </header>

  <!-- Mission Section -->
  <div class="container mb-5">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="assets/images/eco_home.jpg" class="img-fluid rounded shadow-sm" alt="Eco Home">
      </div>
      <div class="col-md-6">
        <h2 class="text-success fw-bold mb-3">Our Mission</h2>
        <p>At DragonStone, we connect conscious consumers with sustainably crafted home and lifestyle products. Each item you purchase helps reduce waste and promote a greener future.</p>
        <p>We believe eco-friendly living should be accessible, stylish, and impactful — because small choices make a big difference.</p>
        <a href="about.php" class="btn btn-outline-success">Learn More</a>
      </div>
    </div>
  </div>

  <!-- Category Section -->
  <div class="container text-center mb-5">
    <h2 class="mb-4 text-success fw-bold">Shop by Category</h2>
    <div class="row g-4 justify-content-center">
      <div class="col-md-4 col-sm-6">
        <div class="card category-card border-0 shadow-sm">
          <img src="assets/images/home.jpg" class="card-img-top" alt="Home & Kitchen">
          <div class="card-body">
            <h5 class="card-title">Home & Kitchen</h5>
            <a href="products.php?category=home" class="btn btn-success btn-sm">View</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card category-card border-0 shadow-sm">
          <img src="assets/images/eco_home.jpg" class="card-img-top" alt="Personal Care">
          <div class="card-body">
            <h5 class="card-title">Personal Care</h5>
            <a href="products.php?category=care" class="btn btn-success btn-sm">View</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card category-card border-0 shadow-sm">
          <img src="assets/images/hero.jpg" class="card-img-top" alt="Outdoor & Garden">
          <div class="card-body">
            <h5 class="card-title">Outdoor & Garden</h5>
            <a href="products.php?category=outdoor" class="btn btn-success btn-sm">View</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CTA Section -->
  <div class="text-center bg-success text-white py-5">
    <h3 class="mb-3">Join Our Green Community</h3>
    <p>Sign up today and earn EcoPoints with every purchase!</p>
    <a href="signup.php" class="btn btn-light btn-lg">Create Account</a>
  </div>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
