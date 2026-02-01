<?php
session_start();
include("../config/db.php");

// 1️⃣ Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../public/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2️⃣ Check if cart exists and is not empty
$cart = $_SESSION['cart'] ?? [];
if(empty($cart)){
    echo "<script>alert('Your cart is empty!'); window.location.href='dashboard.php';</script>";
    exit();
}

// 3️⃣ Insert new order into orders table
$status = 'Pending';
$order_sql = "INSERT INTO orders (user_id, order_date, status) VALUES (?, NOW(), ?)";
$stmt = mysqli_prepare($conn, $order_sql);
if(!$stmt){
    die("Prepare failed: ".mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "is", $user_id, $status);
mysqli_stmt_execute($stmt);

// 4️⃣ Get the new order_id
$order_id = mysqli_insert_id($conn);

// 5️⃣ Loop through cart and insert products into order_items
foreach($cart as $key => $item){
    
    // Handle cart structure: associative array or array of arrays
    if(is_array($item)){
        // If cart is array of arrays with product_id and quantity
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
    } else {
        // If cart is associative array: product_id => quantity
        $product_id = $key;
        $quantity = $item;
    }

    // Get unit price of product
    $p_sql = "SELECT price FROM products WHERE product_id=?";
    $p_stmt = mysqli_prepare($conn, $p_sql);
    mysqli_stmt_bind_param($p_stmt, "i", $product_id);
    mysqli_stmt_execute($p_stmt);
    $product_result = mysqli_stmt_get_result($p_stmt);
    $product = mysqli_fetch_assoc($product_result);

    if(!$product){
        die("Product not found: $product_id");
    }

    $price = $product['price'];

    // Insert into order_items
    $oi_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $oi_stmt = mysqli_prepare($conn, $oi_sql);
    mysqli_stmt_bind_param($oi_stmt, "iiid", $order_id, $product_id, $quantity, $price);

    if(!mysqli_stmt_execute($oi_stmt)){
        die("Order item insert failed for product $product_id: ".mysqli_error($conn));
    }
}

// 6️⃣ Clear cart
unset($_SESSION['cart']);

// 7️⃣ Redirect customer to my_orders page
header("Location: my_orders.php");
exit();
?>