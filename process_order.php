<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';

if ($_SESSION['payment_status'] !== 'success') {
    echo "<div class='container py-5'><p>Payment not completed.</p></div>";
    include 'includes/footer.php';
    exit;
}

$final = $_SESSION['order_total'];
$redeem = $_SESSION['redeem_points'] ?? 0;
$discount = $redeem * 0.01;
$donate = $_SESSION['donate'] ?? false;

if (!empty($_SESSION['user_id']) && !empty($_SESSION['cart'])) {
    $uid = $_SESSION['user_id'];
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare('INSERT INTO orders (user_id, total_amount) VALUES (?, ?)');
        $stmt->bind_param('id', $uid, $final);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        foreach ($_SESSION['cart'] as $pid => $qty) {
            $price_query = $conn->prepare('SELECT price FROM products WHERE id = ?');
            $price_query->bind_param('i', $pid);
            $price_query->execute();
            $price_result = $price_query->get_result()->fetch_assoc();
            $price = $price_result['price'] ?? 0;

            $pstmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
            $pstmt->bind_param('iiid', $order_id, $pid, $qty, $price);
            $pstmt->execute();
        }

        $points_awarded = floor($final * 0.1);

        if ($donate) {
            $d = $conn->prepare('INSERT INTO donations (user_id, points, created_at) VALUES (?, ?, NOW())');
            $d->bind_param('ii', $uid, $points_awarded);
            $d->execute();
        } else {
            $u = $conn->prepare('UPDATE users SET eco_points = eco_points + ? WHERE id = ?');
            $u->bind_param('ii', $points_awarded, $uid);
            $u->execute();
        }

        if ($redeem > 0) {
            $u2 = $conn->prepare('UPDATE users SET eco_points = GREATEST(eco_points - ?, 0) WHERE id = ?');
            $u2->bind_param('ii', $redeem, $uid);
            $u2->execute();
        }

        $conn->commit();

        unset($_SESSION['cart']);
        unset($_SESSION['redeem_points']);
        unset($_SESSION['order_total']);
        unset($_SESSION['payment_status']);

        echo "<div class='container py-5 text-center'>
                <h3 class='text-success'>Payment Successful</h3>
                <p>Order total: R" . number_format($final, 2) . "</p>
                <p>EcoPoints awarded: $points_awarded</p>
                <a href='products.php' class='btn btn-outline-success mt-3'>Continue Shopping</a>
              </div>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<div class='container py-5'><p>Error: " . $e->getMessage() . "</p></div>";
    }
} else {
    echo "<div class='container py-5'><p>No order found or user not logged in.</p></div>";
}

include 'includes/footer.php';
?>
