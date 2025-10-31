<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg" style="background-color:#eef6ef;">
  <div class="container">
    <a class="navbar-brand fw-bold text-success" href="index.php">DragonStone</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">☰</button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="community.php">Community</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>

        <?php if (!empty($_SESSION['user_name'])): ?>
          <li class="nav-item"><span class="nav-link">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
        <?php endif; ?>

        <li class="nav-item"><a class="nav-link" href="admin/admin_login.php">Admin</a></li>
      </ul>
    </div>
  </div>
</nav>
