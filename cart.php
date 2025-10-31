<<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';

// --- Handle Redeem Points Form ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redeem_points'])) {
    $use = max(0, (int)$_POST['redeem_points']);
    $_SESSION['redeem_points'] = $use;
    header('Location: cart.php');
    exit();
}

// --- Handle Remove Item ---
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    unset($_SESSION['cart'][(int)$_GET['id']]);
    header('Location: cart.php');
    exit();
}

// --- Handle Empty Cart ---
if (isset($_GET['action']) && $_GET['action'] === 'empty') {
    unset($_SESSION['cart']);
    header('Location: cart.php');
    exit();
}
?>
<div class="container py-5">
    <h2>Your Shopping Cart</h2>

    <?php
    // Flash message
    if (!empty($_SESSION['flash'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['flash']) . '</div>';
        unset($_SESSION['flash']);
    }

    // --- Display Cart Items ---
    if (!empty($_SESSION['cart'])) {
        $ids = array_keys($_SESSION['cart']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $types = str_repeat('i', count($ids));

        $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id IN ($placeholders)");

        
        $stmt_params = array_merge([$types], $ids);
        $stmt->bind_param(...$stmt_params);

        $stmt->execute();
        $res = $stmt->get_result();

        $products = [];
        while ($r = $res->fetch_assoc()) {
            $products[$r['id']] = $r;
        }

        $total = 0;
        echo '<form method="post"><table class="table table-bordered">';
        echo '<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>';

        foreach ($_SESSION['cart'] as $pid => $qty) {
            if (!isset($products[$pid])) continue;

            $p = $products[$pid];
            $subtotal = $p['price'] * $qty;
            $total += $subtotal;

            echo '<tr>
                    <td>' . htmlspecialchars($p['name']) . '</td>
                    <td>R' . number_format($p['price'], 2) . '</td>
                    <td><input type="number" name="qty[' . $pid . ']" value="' . $qty . '" min="0" style="width:80px" class="form-control"></td>
                    <td>R' . number_format($subtotal, 2) . '</td>
                    <td><a href="cart.php?action=remove&id=' . $pid . '" class="btn btn-danger btn-sm">Remove</a></td>
                  </tr>';
        }

        echo '<tr><td colspan="3"><strong>Total</strong></td><td colspan="2"><strong>R' . number_format($total, 2) . '</strong></td></tr>';
        echo '</table></form>';

        // --- Redeem Points Section ---
        $user_points = 0;
        if (!empty($_SESSION['user_id'])) {
            $uq = $conn->prepare('SELECT eco_points FROM users WHERE id=?');
            $uq->bind_param('i', $_SESSION['user_id']);
            $uq->execute();
            $ur = $uq->get_result()->fetch_assoc();
            if ($ur) $user_points = (int)$ur['eco_points'];
        }

        echo '
        <div class="mb-3">
            <form method="post" class="d-flex">
                <input type="number" name="redeem_points" min="0" max="' . $user_points . '" 
                       value="' . (int)($_SESSION['redeem_points'] ?? 0) . '" 
                       class="form-control me-2" style="width:150px">
                <button class="btn btn-outline-primary">Apply Points</button>
            </form>
        </div>';

        $discount = (isset($_SESSION['redeem_points']) ? ($_SESSION['redeem_points'] * 0.01) : 0);
        $final = max(0, $total - $discount);

        echo '<p>User EcoPoints: <strong>' . $user_points . '</strong> (1 point = R0.01)</p>';
        echo '<p>Discount from points: R' . number_format($discount, 2) . '</p>';
        echo '<p><strong>Final total: R' . number_format($final, 2) . '</strong></p>';
        echo '<a href="checkout.php?donate=0" class="btn btn-success">Checkout</a>
              <a href="checkout.php?donate=1" class="btn btn-secondary">Donate Points at Checkout</a>
              <a href="cart.php?action=empty" class="btn btn-warning">Empty Cart</a>';
    } else {
        echo '<p>Your cart is empty. <a href="products.php">Continue shopping</a></p>';
    }
    ?>
</div>
<?php include 'includes/footer.php'; ?>
