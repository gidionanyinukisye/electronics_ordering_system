<?php
session_start();
include("../config/db.php");

// Check if admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../public/login.html");
    exit();
}

// Get all orders with user info
$sql = "SELECT o.order_id, o.user_id, o.order_date, o.status, u.full_name
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        ORDER BY o.order_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Orders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f1f3f6;}
.container{margin-top:30px;}
.card{border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);}
.table th{background:#007bff;color:#fff;}
.status-badge{padding:5px 10px;border-radius:20px;color:#fff;font-weight:600;}
.status-Pending{background:#ffc107;}
.status-Completed{background:#28a745;}
.status-Cancelled{background:#dc3545;}
a.btn-view{background:#17a2b8;color:#fff;padding:5px 12px;border-radius:5px;text-decoration:none;}
a.btn-view:hover{background:#138496;}
</style>
</head>
<body>
<div class="container">
<h1 class="text-center mb-4">All Orders</h1>

<div class="card p-3">
<table class="table table-hover align-middle">
<thead>
<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Order Date</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td>#<?= $row['order_id']; ?></td>
<td><?= $row['full_name']; ?></td>
<td><?= date("d M Y, H:i", strtotime($row['order_date'])); ?></td>
<td>
    <span class="status-badge status-<?= $row['status']; ?>"><?= $row['status']; ?></span>
</td>
<td><a href="order_details.php?id=<?= $row['order_id']; ?>" class="btn-view">View</a></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</body>
</html>