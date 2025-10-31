<?php include 'includes/header.php'; ?>
<div class="container py-5">
  <h2>Our Products</h2>

  <form method="GET" action="products.php" class="mb-4 d-flex" style="max-width:400px;">
      <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      <button class="btn btn-outline-success">Search</button>
  </form>
