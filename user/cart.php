<?php
session_start();
include("../config/db.php");

/* =========================
   AUTH CHECK
========================= */
if (!isset($_SESSION['auth']) || $_SESSION['role_id'] != 2) {
    header("Location: ../public/login.html");
    exit;
}

/* =========================
   GET CART
========================= */
$cart = $_SESSION['cart'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Cart</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{
    font-family:Segoe UI,sans-serif;
    background:#f4f6f8;
    margin:0;
}
.container{
    width:90%;
    margin:30px auto;
}
h1{color:#2c3e50}
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}
th, td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #ddd;
}
th{
    background:#2c3e50;
    color:white;
}
img{
    width:70px;
    border-radius:8px;
}
input[type=number]{
    width:60px;
    padding:5px;
}
button{
    padding:8px 15px;
    background:#3498db;
    border:none;
    color:white;
    border-radius:5px;
    cursor:pointer;
}
button:hover{
    background:#2980b9;
}
.remove-btn{
    background:#e74c3c;
}
.remove-btn:hover{
    background:#c0392b;
}
.total{
    font-weight:bold;
    font-size:18px;
    color:#27ae60;
    text-align:right;
    margin-top:10px;
}
.place-order{
    display:block;
    margin-top:20px;
    text-decoration:none;
    background:#2ecc71;
    color:white;
    padding:12px 25px;
    border-radius:8px;
    text-align:center;
}
.place-order:hover{
    background:#27ae60;
}
</style>
</head>
<body>

<div class="container">
    <h1>My Cart</h1>

    <?php if(empty($cart)): ?>
        <p>Your cart is empty. <a href="products.php">Go shopping</a></p>
    <?php else: ?>
    <table>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>

        <?php
        $grandTotal = 0;
        foreach($cart as $item):
            $subtotal = $item['price'] * $item['quantity'];
            $grandTotal += $subtotal;
        ?>
        <tr>
            <td><img src="../uploads/<?= $item['image']; ?>" alt="<?= $item['product_name']; ?>"></td>
            <td><?= htmlspecialchars($item['product_name']); ?></td>
            <td>$<?= number_format($item['price'],2); ?></td>
            <td>
                <form method="POST" action="update_cart.php">
                    <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                    <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1">
                    <button type="submit">Update</button>
                </form>
            </td>
            <td>$<?= number_format($subtotal,2); ?></td>
            <td>
                <a class="remove-btn" href="remove_from_cart.php?product_id=<?= $item['product_id']; ?>" onclick="return confirm('Remove this item?')">Remove</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p class="total">Grand Total: $<?= number_format($grandTotal,2); ?></p>

    <a href="place_order.php" class="place-order">Place Order</a>
    <?php endif; ?>
</div>

</body>
</html>