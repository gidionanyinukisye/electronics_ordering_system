<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 2){
    header("Location: ../public/login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['order_id'])){
    header("Location: dashboard.php");
    exit;
}

$order_id = $_GET['order_id'];

// Verify that this order belongs to user
$order_check = mysqli_query($conn,"SELECT * FROM orders WHERE order_id='$order_id' AND user_id='$user_id'");
if(mysqli_num_rows($order_check) == 0){
    header("Location: dashboard.php");
    exit;
}

$order = mysqli_fetch_assoc($order_check);
$items_query = mysqli_query($conn,"SELECT oi.*, p.product_name, p.price FROM order_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id='$order_id'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details</title>
<style>
body{font-family:'Segoe UI',sans-serif;background:#f0f2f5;padding:20px;}
.container{max-width:900px;margin:0 auto;}
h2{color:#0d6efd;margin-bottom:20px;}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,0.1);}
th,td{padding:12px;text-align:left;border-bottom:1px solid #ccc;}
th{background:#e9ecef;}
.status{padding:4px 10px;border-radius:6px;color:#fff;font-weight:bold;font-size:13px;}
.Pending{background:#ffc107;}
.Approved{background:#0d6efd;}
.Delivered{background:#198754;}
.Cancelled{background:#dc3545;}
.back-btn{display:inline-block;margin-top:15px;padding:8px 15px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:6px;}
.back-btn:hover{background:#0b5ed7;}
</style>
</head>
<body>

<div class="container">
<h2>Order #<?= $order['order_id'] ?> Details</h2>
<p><strong>Order Date:</strong> <?= date('d M, Y', strtotime($order['order_date'])) ?></p>
<p><strong>Status:</strong> <span class="status <?= $order['status'] ?>"><?= $order['status'] ?></span></p>

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        while($item = mysqli_fetch_assoc($items_query)){
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
        <tr>
            <td><?= $item['product_name'] ?></td>
            <td>$<?= number_format($item['price'],2) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>$<?= number_format($subtotal,2) ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="3" style="text-align:right;font-weight:bold;">Total</td>
            <td>$<?= number_format($total,2) ?></td>
        </tr>
    </tbody>
</table>

<a class="back-btn" href="dashboard.php#orders">Back to Dashboard</a>

</div>
</body>
</html>