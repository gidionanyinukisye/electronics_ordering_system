<?php
session_start();
include("../config/db.php");

/* =========================
   INIT CART SESSION
========================= */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* =========================
   VALIDATE REQUEST
========================= */
if (!isset($_POST['product_id'])) {
    header("Location: products.php");
    exit;
}

$product_id = (int) $_POST['product_id'];

/* =========================
   FETCH PRODUCT FROM DB
   (SAFEST WAY)
========================= */
$query = mysqli_query($conn, "
    SELECT product_id, product_name, price, stock, image 
    FROM products 
    WHERE product_id = $product_id AND is_deleted = 0
");

if (mysqli_num_rows($query) == 0) {
    die("Product not found");
}

$product = mysqli_fetch_assoc($query);

/* =========================
   CHECK STOCK
========================= */
if ($product['stock'] <= 0) {
    $_SESSION['error'] = "Product is out of stock";
    header("Location: products.php");
    exit;
}

/* =========================
   ADD / UPDATE CART
========================= */
if (isset($_SESSION['cart'][$product_id])) {

    // prevent exceeding stock
    if ($_SESSION['cart'][$product_id]['quantity'] < $product['stock']) {
        $_SESSION['cart'][$product_id]['quantity']++;
    }

} else {

    $_SESSION['cart'][$product_id] = [
        'product_id'   => $product['product_id'],
        'product_name' => $product['product_name'],
        'price'        => $product['price'],
        'quantity'     => 1,
        'image'        => $product['image']
    ];
}

$_SESSION['success'] = "Product added to cart";
header("Location: cart.php");
exit;