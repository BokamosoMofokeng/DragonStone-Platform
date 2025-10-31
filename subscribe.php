<?php
session_start();
include 'db_connect.php';

// Check request type and product ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['product_id'])) {
    header('Location: products.php');
    exit();
}

$pid = (int)$_POST['product_id'];

// Ensure user is logged in
if (empty($_SESSION['user_id'])) {
    $_SESSION['flash'] = 'Please login to subscribe.';
    header('Location: login.php');
    exit();
}

$uid = $_SESSION['user_id'];
$status = 'active';
$rec = 30;


$stmt = $conn->prepare('
    INSERT INTO subscriptions (user_id, product_id, status, recurrence_days, next_billing)
    VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))
');
$stmt->bind_param('iisi', $uid, $pid, $status, $rec);

if ($stmt->execute()) {
    $_SESSION['flash'] = 'Subscription created (trial).';
} else {
    $_SESSION['flash'] = 'Error: ' . $stmt->error;
}

header('Location: products.php');
exit();
?>

