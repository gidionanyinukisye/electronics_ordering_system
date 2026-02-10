<?php
session_start();
include("../config/db.php");

/* =========================
   AUTH CHECK
========================= */
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 2){
    header("Location: ../public/login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

/* =========================
   FETCH USER ORDERS
========================= */
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
.container{width:90%;margin:30px auto;}
h2{text-align:center;color:#0a1a33;margin-bottom:30px;}
.order-card{background:#fff;padding:20px;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,.1);margin-bottom:20px;transition:.3s;}
.order-card:hover{transform:translateY(-5px);box-shadow:0 12px 25px rgba(0,0,0,.2);}
.order-card h3{margin:0;color:#0a1a33;}
.order-card small{color:#777;}
.order-card p{margin:5px 0;}
.view-details{display:inline-block;margin-top:10px;padding:8px 15px;background:#0a1a33;color:#fff;border-radius:6px;text-decoration:none;}
.view-details:hover{background:#124a9f;}
.no-orders{text-align:center;color:red;font-weight:bold;margin-top:50px;}
</style>
</head>
<body>

<div class="container">
<h2>My Orders</h2>

<?php if(mysqli_num_rows($orders_query) == 0){ ?>
    <p class="no-orders">You have not placed any orders yet.</p>
<?php } else { ?>
    <?php while($order = mysqli_fetch_assoc($orders_query)){ ?>
        <div class="order-card">
            <h3>Order #<?= $order['order_id']; ?></h3>
            <p><small>Placed on: <?= $order['created_at']; ?></small></p>
            <p><strong>Total Amount:</strong> $<?= number_format($order['total_amount_calc'],2); ?></p>
            <p><strong>Status:</strong> <?= $order['status']; ?></p>
            <a class="view-details" href="order_details.php?order_id=<?= $order['order_id']; ?>">View Details</a>
        </div>
    <?php } ?>
<?php } ?>

</div>

</body>
</html>