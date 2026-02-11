<?php
session_start();
include("../config/db.php");

// Authentication (Admin only)
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit;
}

// Fetch all orders with user info
$orders_query = mysqli_query($conn, "
    SELECT o.*, u.full_name, u.email, SUM(oi.quantity*oi.price) AS total_amount
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.user_id
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    GROUP BY o.order_id
    ORDER BY o.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Orders | Electronics Ordering System</title>
<style>
body{font-family:Segoe UI,sans-serif;background:#f4f6fb;margin:0;padding:0;}
.container{width:95%;margin:auto;padding:30px 0;}
h2{text-align:center;margin-bottom:30px;color:#0a1a33;}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,.2);}
th, td{padding:12px 15px;text-align:center;border-bottom:1px solid #ddd;}
th{background:#0a1a33;color:#fff;}
tr:hover{background:#f1f1f1;}
.status-Pending{color:orange;font-weight:bold;}
.status-Confirmed{color:blue;font-weight:bold;}
.status-Completed{color:green;font-weight:bold;}
.status-Cancelled{color:red;font-weight:bold;}
select{padding:6px 10px;border-radius:6px;border:1px solid #ccc;}
button.update-btn{padding:6px 12px;background:#124a9f;color:#fff;border:none;border-radius:6px;cursor:pointer;transition:.3s;}
button.update-btn:hover{background:#0a1a33;}
</style>
</head>
<body>

<div class="container">
    <h2>Manage Orders</h2>

    <?php if(mysqli_num_rows($orders_query) == 0){ ?>
        <p style="text-align:center;">No orders found.</p>
    <?php } else { ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while($order = mysqli_fetch_assoc($orders_query)){ ?>
                <tr>
                    <td>#<?php echo $order['order_id']; ?></td>
                    <td><?php echo $order['full_name']; ?></td>
                    <td><?php echo $order['email']; ?></td>
                    <td>$<?php echo number_format($order['total_amount'],2); ?></td>
                    <td class="status-<?php echo $order['status']; ?>"><?php echo $order['status']; ?></td>
                    <td><?php echo date("d M Y H:i", strtotime($order['created_at'])); ?></td>
                    <td>
                        <form method="POST" action="update_order.php">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <select name="status" required>
                                <option value="">--Change Status--</option>
                                <option value="Pending">Pending</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <button type="submit" class="update-btn">Update</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</div>

</body>
</html>