<?php
session_start();
include 'includes/header.php';

if (!isset($_SESSION['order_total'])) {
    echo "<div class='container py-5'><p>No payment to process.</p></div>";
    include 'includes/footer.php';
    exit;
}

$total = $_SESSION['order_total'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['card_name']) || empty($_POST['card_number'])) {
        $error = "Please fill in all fields.";
    } else {
        $_SESSION['payment_status'] = 'success';
        header("Location: process_order.php");
        exit;
    }
}
?>

<div class="container py-5">
  <h2 class="mb-4 text-success">Payment Gateway</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="POST" class="shadow p-4 bg-white rounded" style="max-width:400px;">
    <div class="mb-3">
      <label class="form-label">Cardholder Name</label>
      <input type="text" name="card_name" class="form-control" placeholder="John Doe">
    </div>
    <div class="mb-3">
      <label class="form-label">Card Number</label>
      <input type="text" name="card_number" class="form-control" placeholder="1111 2222 3333 4444">
    </div>
    <div class="row">
      <div class="col">
        <label class="form-label">Expiry</label>
        <input type="text" name="exp" class="form-control" placeholder="MM/YY">
      </div>
      <div class="col">
        <label class="form-label">CVV</label>
        <input type="password" name="cvv" class="form-control" maxlength="3" placeholder="123">
      </div>
    </div>
    <div class="mt-4">
      <button type="submit" class="btn btn-success w-100">Pay R<?php echo number_format($total, 2); ?></button>
    </div>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
