<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: ../public/login.html");
    exit();
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$order = mysqli_query($conn, "
SELECT * FROM orders
WHERE order_id=$order_id AND user_id=$user_id
");

$o = mysqli_fetch_assoc($order);

if (!$o) {
    die("Order not found");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h4>Order Details</h4>

    <ul class="list-group">
        <li class="list-group-item"><strong>Order ID:</strong> <?= $o['order_id']; ?></li>
        <li class="list-group-item"><strong>Date:</strong> <?= $o['order_date']; ?></li>
        <li class="list-group-item"><strong>Status:</strong> <?= $o['status']; ?></li>
    </ul>

    <a href="my_orders.php" class="btn btn-secondary mt-3">Back</a>
</div>

</body>
</html>