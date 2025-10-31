<?php
session_start();
include 'db_connect.php';

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['product_id'])) {
    header('Location: products.php');
    exit();
}

// Get product ID and quantity
$product_id = (int)$_POST['product_id'];
$qty = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;

// Verify product exists
$stmt = $conn->prepare('SELECT id FROM products WHERE id = ?');
$stmt->bind_param('i', $product_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    header('Location: products.php');
    exit();
}

// Initialize or update session cart
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $qty;
} else {
    $_SESSION['cart'][$product_id] = $qty;
}

// Flash message + redirect
$_SESSION['flash'] = 'Item added to cart.';
header('Location: cart.php');
exit();
?>
