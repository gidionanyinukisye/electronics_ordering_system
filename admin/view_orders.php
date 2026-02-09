<?php
session_start();
include("../config/db.php");

/* ===== ADMIN AUTH ===== */
if (!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1) {
    header("Location: ../public/login.html");
    exit;
}

/* ===== GET USER ID ===== */
if (!isset($_GET['user_id'])) {
    header("Location: users.php");
    exit;
}

$user_id = intval($_GET['user_id']);

/* ===== GET USER INFO ===== */
$user_q = mysqli_query($conn, "SELECT * FROM users WHERE user_id=$user_id");
$user = mysqli_fetch_assoc($user_q);

if (!$user) {
    header("Location: users.php");
    exit;
}

/* ===== GET ORDERS + ITEMS ===== */
$sql = "
SELECT 
    o.order_id,
    o.order_date,
    o.status AS order_status,
    p.product_name,
    p.price,
    oi.quantity,
    (p.price * oi.quantity) AS total
FROM orders o
JOIN order_items oi ON o.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id
WHERE o.user_id = $user_id
ORDER BY o.order_id DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin | View Orders</title>
<style>
body{
    background:#f4f6f9;
    font-family:'Segoe UI', sans-serif;
    padding:20px;
}
h2{
    color:#0d6efd;
    margin-bottom:10px;
}
.user-box{
    background:white;
    padding:15px;
    border-radius:8px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
    margin-bottom:15px;
}
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}
th, td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:center;
}
th{
    background:#0d6efd;
    color:white;
}
tr:hover{
    background:#f1f1f1;
}
select{
    font-weight:600;
     padding:8px;
    width:140px;
    border-radius:6px;
    border:1px solid #ccc;
    background:#fff;
    cursor:pointer;
}
.pending{color:#ffc107;}
.approved{color:#0d6efd;}
.shipped{color:#6f42c1;}
.delivered{color:#198754;}
.cancelled{color:#dc3545;}
.total-box{
    margin-top:15px;
    text-align:right;
    font-size:18px;
    font-weight:bold;
}
.back{
    margin-top:15px;
}
.back a{
    text-decoration:none;
    color:#6c757d;
}
</style>
</head>
<body>

<h2>📦 Orders – <?= htmlspecialchars($user['full_name']) ?></h2>

<div class="user-box">
    <b>Email:</b> <?= htmlspecialchars($user['email']) ?><br>
    <b>Account Status:</b> <?= strtoupper($user['status']) ?>
</div>

<table>
<tr>
    <th>Order ID</th>
    <th>Order Date</th>
    <th>Status</th>
    <th>Product</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
</tr>

<?php
$grand_total = 0;
if (mysqli_num_rows($result) > 0):
while ($row = mysqli_fetch_assoc($result)):
$grand_total += $row['total'];
?>
<tr>
    <td>#<?= $row['order_id'] ?></td>
    <td><?= date("d M Y", strtotime($row['order_date'])) ?></td>
    <td>
        <form method="POST" action="update_order_status.php">
            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
            <select name="status" onchange="this.form.submit()"
                 style="width:145px;">
                <option value="pending"   <?= $row['order_status']=='pending'?'selected':'' ?>>Pending</option>
                <option value="approved"  <?= $row['order_status']=='approved'?'selected':'' ?>>Approved</option>
                <option value="shipped"   <?= $row['order_status']=='shipped'?'selected':'' ?>>Shipped</option>
                <option value="delivered" <?= $row['order_status']=='delivered'?'selected':'' ?>>Delivered</option>
                <option value="cancelled" <?= $row['order_status']=='cancelled'?'selected':'' ?>>Cancelled</option>
            </select>
        </form>
    </td>
    <td><?= htmlspecialchars($row['product_name']) ?></td>
    <td><?= number_format($row['price']) ?> TZS</td>
    <td><?= $row['quantity'] ?></td>
    <td><?= number_format($row['total']) ?> TZS</td>
</tr>
<?php endwhile; else: ?>
<tr>
    <td colspan="7">No orders found for this user</td>
</tr>
<?php endif; ?>
</table>

<div class="total-box">
    Grand Total: <?= number_format($grand_total) ?> TZS
</div>

<div class="back">
    <a href="users.php">← Back to Manage Users</a>
</div>

</body>
</html>