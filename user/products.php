<?php
session_start();
include("../config/db.php"); // database connection

// Get all products with category name
$sql = "SELECT p.*, c.category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.category_id
        ORDER BY p.product_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Products</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body{font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:0;}
        .container{width:90%;margin:auto;padding:20px;}
        h1{text-align:center;margin-bottom:20px;}
        .top-nav{display:flex;justify-content:flex-end;margin-bottom:20px;}
        .top-nav a{padding:10px 15px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;margin-left:10px;}
        .top-nav a:hover{background:#0056b3;}
        .products{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:20px;}
        .product-card{background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 10px rgba(0,0,0,0.2);}
        .product-card img{width:100%;height:200px;object-fit:cover;border-radius:8px;}
        .product-card h3{margin:10px 0 5px;}
        .product-card p{margin:5px 0;}
        .product-card form{margin-top:10px;display:flex;justify-content:space-between;align-items:center;}
        .product-card input[type="number"]{width:60px;padding:5px;}
        .product-card button{padding:5px 10px;background:#28a745;color:#fff;border:none;border-radius:5px;cursor:pointer;}
        .product-card button:hover{background:#218838;}
    </style>
</head>
<body>

<div class="container">
    <h1>Our Products</h1>

    <!-- Top navigation / View Cart -->
    <div class="top-nav">
        <a href="place_order.php">View Cart / Place Order</a>
    </div>

    <!-- Products Grid -->
    <div class="products">
        <?php while($product = mysqli_fetch_assoc($result)) { ?>
        <div class="product-card">
            <img src="../assets/images/<?= $product['image']; ?>" alt="<?= $product['product_name']; ?>">
            <h3><?= $product['product_name']; ?></h3>
            <p><strong>Category:</strong> <?= $product['category_name']; ?></p>
            <p><?= $product['description']; ?></p>
            <p><strong>Price:</strong> $<?= number_format($product['price'],2); ?></p>

            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
        <?php } ?>
    </div>
</div>

</body>
</html>