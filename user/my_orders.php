<?php
session_start();
include("../config/db.php");

/* =========================
   AUTH CHECK (USER)
========================= */
if (!isset($_SESSION['auth']) || $_SESSION['role_id'] != 2) {
    header("Location: ../public/login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

/* =========================
   FETCH USER ORDERS
========================= */
$orders = mysqli_query($conn, "
    SELECT * FROM orders 
    WHERE user_id = $user_id 
    ORDER BY order_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders</title>

<style>
body{
    font-family:Segoe UI, sans-serif;
    background:#f4f6fb;
    margin:0;
}
.container{
    width:90%;
    margin:auto;
    padding:30px 0;
}
.order-card{
    background:#fff;
    border-radius:12px;
    padding:20px;
    margin-bottom:25px;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
}
.order-header{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
}
.order-header h3{
    margin:0;
}
.badge{
    padding:6px 14px;
    border-radius:20px;
    font-size:14px;
    color:#fff;
}
.Pending{background:#f0ad4e;}
.Approved{background:#0275d8;}
.Delivered{background:#5cb85c;}
.Cancelled{background:#d9534f;}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}
th,td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}
th{
    background:#0a1a33;
    color:#fff;
}
img{
    width:60px;
    height:60px;
    object-fit:contain;
}
.total{
    font-weight:bold;
}
</style>
</head>

<body>

<div class="container">
    <h2>📦 My Orders</h2>

<?php if (mysqli_num_rows($orders) == 0) { ?>
    <p>You have not placed any orders yet.</p>
<?php } ?>

<?php while ($order = mysqli_fetch_assoc($orders)) { ?>

<div class="order-card">

    <div class="order-header">
        <h3>Order #<?php echo $order['order_id']; ?></h3>
        <span class="badge <?php echo $order['status']; ?>">
            <?php echo $order['status']; ?>
        </span>
    </div>

    <p>
        <strong>Date:</strong> <?php echo $order['created_at']; ?><br>
        <strong>Payment:</strong> <?php echo $order['payment_method']; ?><br>
        <strong>Total:</strong> $<?php echo number_format($order['total_amount'],2); ?>
    </p>

    <!-- ORDER ITEMS -->
    <table>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>

        <?php
        $items = mysqli_query($conn, "
            SELECT oi.*, p.product_name, p.image 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.product_id
            WHERE oi.order_id = ".$order['order_id']."
        ");

        while ($item = mysqli_fetch_assoc($items)) {
            $subtotal = $item['price'] * $item['quantity'];
        ?>
        <tr>
            <td>
                <img src="../assets/images/products/<?php echo $item['image']; ?>">
            </td>
            <td><?php echo $item['product_name']; ?></td>
            <td>$<?php echo number_format($item['price'],2); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo number_format($subtotal,2); ?></td>
        </tr>
        <?php } ?>

        <tr>
            <td colspan="4" class="total">ORDER TOTAL</td>
            <td class="total">$<?php echo number_format($order['total_amount'],2); ?></td>
        </tr>

    </table>

</div>

<?php } ?>

</div>

</body>
</html>