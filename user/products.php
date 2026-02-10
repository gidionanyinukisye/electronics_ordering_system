<?php
session_start();
include("../config/db.php");

// Authentication: user only
if(!isset($_SESSION['auth']) || $_SESSION['role_id'] != 2){
    header("Location: ../public/login.html");
    exit;
}

// Fetch products
$query = mysqli_query($conn, "SELECT p.*, c.category_name FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.category_id
                              ORDER BY p.product_id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Products | ElectroHub</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:25px;
    margin-top:30px;
}

.product-card{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
    transition:.35s;
    text-align:center;
}

.product-card:hover{
    transform:translateY(-6px);
    box-shadow:0 12px 30px rgba(0,0,0,.12);
}

.product-card img{
    width:100%;
    height:160px;
    object-fit:contain;
    background:#f8f9fc;
    padding:12px;
}

.product-info{
    padding:15px;
}

.product-info h4{
    font-size:18px;
    margin-bottom:6px;
}

.product-info small{
    color:#777;
    display:block;
    margin-bottom:10px;
}

.product-info button{
    background:#0a1a33;
    color:#fff;
    border:none;
    padding:10px 18px;
    border-radius:20px;
    cursor:pointer;
    font-weight:600;
    transition:.3s;
}

.product-info button:hover{
    background:#124a9f;
}
</style>
</head>
<body>

<h2 style="text-align:center;margin-top:30px;">Available Products</h2>

<div class="grid">
<?php while($row = mysqli_fetch_assoc($query)) { ?>
    <div class="product-card">
        <img src="../assets/images/products/<?php echo $row['image']; ?>" alt="<?php echo $row['product_name']; ?>">
        <div class="product-info">
            <h4><?php echo $row['product_name']; ?></h4>
            <small>Category: <?php echo $row['category_name']; ?></small>
            <small>Price: $<?php echo number_format($row['price'],2); ?></small>
            <form method="POST" action="cart.php">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
                <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        </div>
    </div>
<?php } ?>
</div>

</body>
</html>