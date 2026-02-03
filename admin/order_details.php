<?php
session_start();
include("../config/db.php");

// Check if admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../public/login.html");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];

// Get order info
$order_sql = "SELECT o.*, u.full_name, u.email
              FROM orders o
              JOIN users u ON o.user_id = u.user_id
              WHERE o.order_id=?";
$order_stmt = mysqli_prepare($conn, $order_sql);
mysqli_stmt_bind_param($order_stmt, "i", $order_id);
mysqli_stmt_execute($order_stmt);
$order_res = mysqli_stmt_get_result($order_stmt);
$order = mysqli_fetch_assoc($order_res);

if(!$order){
    die("Order not found.");
}

// Get order items
$items_sql = "SELECT oi.*, p.product_name
              FROM order_items oi
              JOIN products p ON oi.product_id = p.product_id
              WHERE oi.order_id=?";
$items_stmt = mysqli_prepare($conn, $items_sql);
mysqli_stmt_bind_param($items_stmt, "i", $order_id);
mysqli_stmt_execute($items_stmt);
$items_res = mysqli_stmt_get_result($items_stmt);

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details - #<?= $order['order_id']; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f1f3f6;}
.container{margin-top:30px;}
.card{border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);}
.status-badge{padding:5px 10px;border-radius:20px;color:#fff;font-weight:600;}
.status-Pending{background:#ffc107;}
.status-Completed{background:#28a745;}
.status-Cancelled{background:#dc3545;}
.table th{background:#007bff;color:#fff;}
tfoot td{font-weight:bold;}
</style>
</head>
<body>
<div class="container">
<h1 class="text-center mb-4">Order #<?= $order['order_id']; ?></h1>

<div class="card p-4 mb-4">
<h5>Customer: <?= $order['full_name']; ?> (<?= $order['email']; ?>)</h5>
<h6>Status: <span class="status-badge status-<?= $order['status']; ?>"><?= $order['status']; ?></span></h6>
<h6>Order Date: <?= date("d M Y, H:i", strtotime($order['order_date'])); ?></h6>
</div>

<div class="card p-4">
<h4>Order Items</h4>
<table class="table table-hover">
<thead>
<tr>
<th>Product</th>
<th>Price ($)</th>
<th>Quantity</th>
<th>Subtotal ($)</th>
</tr>
</thead>
<tbody>
<?php while($item = mysqli_fetch_assoc($items_res)) { 
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
?>
<tr>
<td><?= $item['product_name']; ?></td>
<td><?= number_format($item['price'],2); ?></td>
<td><?= $item['quantity']; ?></td>
<td><?= number_format($subtotal,2); ?></td>
</tr>
<?php } ?>
</tbody>
<tfoot>
<tr>
<td colspan="3">Total</td>
<td><?= number_format($total,2); ?></td>
</tr>
</tfoot>
</table>
</div>
</body>
</html>