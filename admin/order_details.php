<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit();
}

include("../config/db.php");

if(!isset($_GET['id'])){
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['id']);

/* Order + customer */
$order_sql = "
SELECT 
    o.order_id,
    o.order_date,
    o.status,
    u.name,
    u.email
FROM orders o
JOIN users u ON o.user_id = u.user_id
WHERE o.order_id = '$order_id'
";

$order = mysqli_query($conn, $order_sql);
if(!$order){
    die(mysqli_error($conn));
}

$order_data = mysqli_fetch_assoc($order);

/* Order items */
$item_sql = "
SELECT 
    p.product_name,
    oi.quantity,
    oi.price,
    (oi.quantity * oi.price) AS subtotal
FROM order_items oi
JOIN products p ON oi.product_id = p.product_id
WHERE oi.order_id = '$order_id'
";

$items = mysqli_query($conn, $item_sql);
if(!$items){
    die(mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Order Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <h2>Order #<?= $order_data['order_id']; ?></h2>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Customer:</strong> <?= $order_data['name']; ?></p>
            <p><strong>Email:</strong> <?= $order_data['email']; ?></p>
            <p><strong>Status:</strong> 
                <span class="badge bg-info"><?= $order_data['status']; ?></span>
            </p>
            <p><strong>Date:</strong> <?= $order_data['order_date']; ?></p>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>

        <?php 
        $grand_total = 0;
        while($row = mysqli_fetch_assoc($items)):
            $grand_total += $row['subtotal'];
        ?>
        <tr>
            <td><?= $row['product_name']; ?></td>
            <td><?= number_format($row['price'],2); ?></td>
            <td><?= $row['quantity']; ?></td>
            <td><?= number_format($row['subtotal'],2); ?></td>
        </tr>
        <?php endwhile; ?>

        <tr class="table-success">
            <th colspan="3">Total</th>
            <th><?= number_format($grand_total,2); ?></th>
        </tr>

        </tbody>
    </table>

    <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
</div>

</body>
</html>