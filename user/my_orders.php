<?php
session_start();
include("../config/db.php");

// Authentication
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 2){
    header("Location: ../public/login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all orders for this user
$orders_query = mysqli_query($conn, "
    SELECT o.*, 
           SUM(oi.quantity * oi.price) AS total_amount_calc
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.user_id = $user_id
    GROUP BY o.order_id
    ORDER BY o.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders | Electronics Ordering System</title>
<style>
body{font-family:Segoe UI,sans-serif;background:#f4f6fb;margin:0;padding:0;}
.container{width:90%;margin:auto;padding:30px 0;}
h2{text-align:center;margin-bottom:30px;color:#0a1a33;}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,.1);}
th, td{padding:12px 15px;text-align:center;border-bottom:1px solid #ddd;}
th{background:#0a1a33;color:#fff;}
tr:hover{background:#f1f1f1;}
.status-pending{color:orange;font-weight:bold;}
.status-completed{color:green;font-weight:bold;}
.status-cancelled{color:red;font-weight:bold;}
.view-btn{padding:6px 12px;background:#124a9f;color:#fff;border:none;border-radius:6px;text-decoration:none;transition:.3s;}
.view-btn:hover{background:#0a1a33;}
</style>
</head>
<body>

<div class="container">
    <h2>My Orders / Ordering History</h2>

    <?php if(mysqli_num_rows($orders_query) == 0){ ?>
        <p style="text-align:center;">You have not placed any orders yet.</p>
    <?php } else { ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while($order = mysqli_fetch_assoc($orders_query)){ ?>
                <tr>
                    <td>#<?php echo $order['order_id']; ?></td>
                    <td>$<?php echo number_format($order['total_amount_calc'],2); ?></td>
                    <td class="status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></td>
                    <td><?php echo date("d M Y H:i", strtotime($order['created_at'])); ?></td>
                    <td>
                        <a class="view-btn" href="order_details.php?order_id=<?php echo $order['order_id']; ?>">View Details</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</div>

</body>
</html>