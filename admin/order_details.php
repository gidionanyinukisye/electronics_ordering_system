<?php
session_start();
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}
include("../config/db.php");

$id = $_GET['id'];

$order = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT o.*,u.full_name,u.email
FROM orders o
JOIN users u ON o.user_id=u.user_id
WHERE o.order_id=$id
"));

$items = mysqli_query($conn,"
SELECT p.product_name,oi.quantity,oi.price
FROM order_items oi
JOIN products p ON oi.product_id=p.product_id
WHERE oi.order_id=$id
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Order Details</title>
<style>
body{font-family:Arial;background:#f4f6f9}
.box{width:80%;margin:30px auto;background:#fff;padding:20px}
table{width:100%;border-collapse:collapse}
th,td{padding:10px;border-bottom:1px solid #ddd}
th{background:#0d6efd;color:#fff}
</style>
</head>
<body>

<div class="box">
<h2>Order #<?= $id ?></h2>
<p><b>Name:</b> <?= $order['full_name'] ?></p>
<p><b>Email:</b> <?= $order['email'] ?></p>
<p><b>Status:</b> <?= $order['status'] ?></p>

<h3>Products</h3>
<table>
<tr>
<th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th>
</tr>

<?php $total=0; while($i=mysqli_fetch_assoc($items)){
$sub = $i['price']*$i['quantity']; $total+=$sub;
?>
<tr>
<td><?= $i['product_name'] ?></td>
<td><?= $i['price'] ?></td>
<td><?= $i['quantity'] ?></td>
<td><?= $sub ?></td>
</tr>
<?php } ?>

<tr>
<th colspan="3">TOTAL</th>
<th><?= $total ?></th>
</tr>
</table>
</div>

</body>
</html>