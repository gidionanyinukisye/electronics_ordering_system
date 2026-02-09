<?php
session_start();
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}
include("../config/db.php");

$orders = mysqli_query($conn,"
SELECT o.order_id,o.order_date,o.status,u.full_name
FROM orders o
JOIN users u ON o.user_id=u.user_id
ORDER BY o.order_id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Orders</title>
<style>
body{font-family:Arial;background:#f4f6f9}
table{width:90%;margin:30px auto;background:#fff;border-collapse:collapse}
th,td{padding:12px;border-bottom:1px solid #ddd}
th{background:#0d6efd;color:#fff}
a{color:#0d6efd}
</style>
</head>
<body>

<h2 style="text-align:center">All Orders</h2>

<table>
<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($o=mysqli_fetch_assoc($orders)){ ?>
<tr>
<td>#<?= $o['order_id'] ?></td>
<td><?= $o['full_name'] ?></td>
<td><?= $o['order_date'] ?></td>
<td><?= $o['status'] ?></td>
<td>
<a href="order_details.php?id=<?= $o['order_id'] ?>">View</a>
</td>
</tr>
<?php } ?>

</table>
</body>