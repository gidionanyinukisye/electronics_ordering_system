<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: ../public/login.html");
    exit();
}

$product_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$product = mysqli_query($conn, "SELECT * FROM products WHERE product_id=$product_id");
$p = mysqli_fetch_assoc($product);

if (!$p || $p['stock'] <= 0) {
    die("Product not available");
}

if (isset($_POST['order'])) {
    mysqli_query($conn, "
        INSERT INTO orders (user_id, order_date, status)
        VALUES ($user_id, NOW(), 'Pending')
    ");

    mysqli_query($conn, "
        UPDATE products SET stock = stock - 1
        WHERE product_id = $product_id
    ");

    header("Location: my_orders.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h4>Confirm Order</h4>

    <div class="card p-4">
        <p><strong>Product:</strong> <?= $p['product_name']; ?></p>
        <p><strong>Price:</strong> TZS <?= number_format($p['price']); ?></p>

        <form method="POST">
            <button name="order" class="btn btn-success">Confirm Order</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>