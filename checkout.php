<?php
session_start();
include 'db_connect.php';
include 'includes/header.php';

if (empty($_SESSION['cart'])) {
    echo '<div class="container py-5"><p>Your cart is empty.</p></div>';
    include 'includes/footer.php';
    exit();
}

$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));

$stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
$stmt_params = array_merge([$types], $ids);
$stmt->bind_param(...$stmt_params);
$stmt->execute();
$res = $stmt->get_result();

$products = [];
$total = 0;
while ($r = $res->fetch_assoc()) {
    $products[$r['id']] = $r;
    $total += $r['price'] * $_SESSION['cart'][$r['id']];
}

$redeem = $_SESSION['redeem_points'] ?? 0;
$discount = $redeem * 0.01;
$final = max(0, $total - $discount);
$donate = isset($_GET['donate']) && $_GET['donate'] == '1';

$_SESSION['order_total'] = $final;
$_SESSION['donate'] = $donate;

header("Location: fake_payment.php");
exit();
?>
