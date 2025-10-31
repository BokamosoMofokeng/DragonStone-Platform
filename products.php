<?php
include 'db_connect.php';
include 'includes/header.php';
?>

<div class="container py-5">
  <h2 class="text-center mb-4 text-success fw-bold">Our Products</h2>

  <!-- Search Form -->
  <form method="GET" action="" class="mb-4 text-center">
    <input type="text" name="search" placeholder="Search products..." 
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
           class="form-control d-inline-block w-50" />
    <button type="submit" class="btn btn-success">Search</button>
  </form>

  <?php
  // Handle search
  $search = "";
  if (isset($_GET['search']) && !empty($_GET['search'])) {
      $search = trim($_GET['search']);
      $stmt = $conn->prepare("
          SELECT id, name, description, price, image 
          FROM products 
          WHERE name LIKE CONCAT('%', ?, '%') 
             OR description LIKE CONCAT('%', ?, '%')
      ");
      $stmt->bind_param("ss", $search, $search);
  } else {
      $stmt = $conn->prepare("SELECT id, name, description, price, image FROM products");
  }

  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      echo '<div class="row">';
      while ($row = $result->fetch_assoc()) {
         
          $imageFile = !empty($row['image']) ? htmlspecialchars($row['image']) : 'default.jpg';
          $imagePath = 'assets/images/' . $imageFile;

          echo '
          <div class="col-md-4 mb-4">
              <div class="card h-100 shadow-sm border-0">
                  <img src="' . $imagePath . '" class="card-img-top" 
                       alt="' . htmlspecialchars($row['name']) . '" 
                       onerror="this.src=\'assets/images/default.jpg\'">
                  <div class="card-body text-center">
                      <h5 class="card-title text-success">' . htmlspecialchars($row['name']) . '</h5>
                      <p class="card-text">' . htmlspecialchars(substr($row['description'], 0, 80)) . '...</p>
                      <p><strong>R' . number_format($row['price'], 2) . '</strong></p>
                      <form method="POST" action="add_to_cart.php" class="mt-2">
                          <input type="hidden" name="product_id" value="' . $row['id'] . '">
                          <input type="number" name="qty" value="1" min="1" 
                                 class="form-control mb-2 mx-auto" style="width:80px;">
                          <button type="submit" class="btn btn-success btn-sm">Add to Cart</button>
                      </form>
                  </div>
              </div>
          </div>';
      }
      echo '</div>';
  } else {
      echo '<p class="text-center">No products found.</p>';
  }
  ?>

</div>

<?php include 'includes/footer.php'; ?>
