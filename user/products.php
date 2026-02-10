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

/* =========================
   HANDLE ADD TO CART
========================= */
if(isset($_POST['add_to_cart'])){
    $product_id = (int) $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = (float) $_POST['price'];
    $image = $_POST['image'];
    $quantity = 1; // default quantity

    // Initialize cart if not exists
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
    }

    // Add to cart or update quantity if exists
    if(isset($_SESSION['cart'][$product_id])){
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'image' => $image,
            'quantity' => $quantity
        ];
    }

    // Redirect back to products to avoid resubmission
    header("Location: products.php");
    exit;
}

/* =========================
   FETCH PRODUCTS (ONLY ACTIVE)
========================= */
$query = mysqli_query($conn, "
    SELECT p.*, c.category_name 
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    WHERE p.is_deleted = 0
    ORDER BY p.product_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Products | Electronics Ordering System</title>
<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#f4f6fb;
}
.container{
    width:90%;
    margin:auto;
    padding:30px 0;
}
h2{
    text-align:center;
    margin-bottom:30px;
}
.products{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
}
.card{
    background:#fff;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
    overflow:hidden;
    transition:.3s;
}
.card:hover{
    transform:translateY(-6px);
}
.card img{
    width:100%;
    height:220px;
    object-fit:contain;
    background:#f8f9fc;
}
.card-content{
    padding:15px;
}
.card-content h4{
    margin:5px 0;
}
.card-content small{
    color:#777;
}
.price{
    font-size:18px;
    font-weight:bold;
    margin:8px 0;
}
button{
    width:100%;
    padding:10px;
    background:#0a1a33;
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
button:hover{
    background:#124a9f;
}
.out{
    color:red;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="container">
    <h2>Available Products</h2>

    <div class="products">

    <?php if(mysqli_num_rows($query) == 0){ ?>
        <p>No products available</p>
    <?php } ?>

    <?php while($row = mysqli_fetch_assoc($query)){ ?>
        <div class="card">
            <img src="../assets/images/products/<?php echo $row['image']; ?>" alt="<?php echo $row['product_name']; ?>">

            <div class="card-content">
                <small><?php echo $row['category_name']; ?></small>
                <h4><?php echo $row['product_name']; ?></h4>
                <p><?php echo substr($row['description'],0,60); ?>...</p>

                <?php if($row['stock'] > 0){ ?>
                    <div class="price">$<?php echo number_format($row['price'],2); ?></div>

                    <!-- ADD TO CART FORM -->
                    <form method="POST" action="">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
                        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                        <input type="hidden" name="image" value="<?php echo $row['image']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                <?php } else { ?>
                    <p class="out">Out of Stock</p>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    </div>
</div>

</body>
</html>