<?php
include("config/db.php");

// Latest products
$products = mysqli_query($conn,"
    SELECT * FROM products
    WHERE is_deleted = 0 AND stock > 0
    ORDER BY product_id DESC
    LIMIT 8
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Electronics Ordering System</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI, sans-serif;
}
body{
    background:#f4f6fb;
    color:#222;
}

/* NAV */
nav{
    background:#0a1a33;
    padding:15px 8%;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
nav h2{
    color:#fff;
}
nav a{
    color:#fff;
    margin-left:20px;
    text-decoration:none;
    font-weight:500;
}

/* HERO */
.hero{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:70px 8%;
    background:linear-gradient(120deg,#0a1a33,#123a7c);
    color:#fff;
}
.hero-text{
    max-width:50%;
}
.hero-text h1{
    font-size:45px;
    margin-bottom:15px;
}
.hero-text p{
    font-size:18px;
    margin-bottom:25px;
}
.hero-text a{
    background:#ffb703;
    padding:14px 25px;
    border-radius:30px;
    color:#000;
    font-weight:bold;
    text-decoration:none;
}
.hero img{
    width:420px;
}

/* SECTIONS */
.section{
    padding:60px 8%;
}
.section h2{
    text-align:center;
    margin-bottom:40px;
}

/* PRODUCTS GRID */
.products{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
}
.card{
    background:#fff;
    border-radius:15px;
    box-shadow:0 8px 25px rgba(0,0,0,.1);
    overflow:hidden;
    transition:.3s;
}
.card:hover{
    transform:translateY(-8px);
}
.card img{
    width:100%;
    height:220px;
    object-fit:contain;
    background:#f8f9fc;
}
.card-body{
    padding:15px;
}
.card-body h4{
    margin:8px 0;
}
.price{
    font-size:18px;
    font-weight:bold;
    color:#0a1a33;
}

/* WHY US */
.why{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
}
.why div{
    background:#fff;
    padding:30px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
}

/* CTA */
.cta{
    background:#0a1a33;
    color:#fff;
    text-align:center;
    padding:60px 8%;
}
.cta a{
    background:#ffb703;
    padding:15px 30px;
    color:#000;
    border-radius:30px;
    text-decoration:none;
    font-weight:bold;
}

/* FOOTER */
footer{
    background:#020d1f;
    color:#ccc;
    text-align:center;
    padding:20px;
}
</style>
</head>

<body>

<!-- NAV -->
<nav>
    <h2>ElectroStore</h2>
    <div>
        <a href="index.php">Home</a>
        <a href="user/products.php">Products</a>
        <a href="public/login.html">Login</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-text">
        <h1>Modern Electronics Store</h1>
        <p>Buy latest phones, laptops, accessories with trusted quality and fast ordering system.</p>
        <a href="user/products.php">Shop Now</a>
    </div>
    <img src="assets/images/products/phone1.jpg">
</section>

<!-- FEATURED -->
<section class="section">
    <h2>Latest Products</h2>

    <div class="products">
        <?php while($p = mysqli_fetch_assoc($products)){ ?>
        <div class="card">
            <img src="assets/images/products/<?php echo $p['image']; ?>">
            <div class="card-body">
                <h4><?php echo $p['product_name']; ?></h4>
                <p><?php echo substr($p['description'],0,50); ?>...</p>
                <div class="price">$<?php echo number_format($p['price'],2); ?></div>
            </div>
        </div>
        <?php } ?>
    </div>
</section>

<!-- WHY US -->
<section class="section">
    <h2>Why Choose Us</h2>
    <div class="why">
        <div>
            <h3>✔ Genuine Products</h3>
            <p>We sell 100% original electronics.</p>
        </div>
        <div>
            <h3>🚚 Fast Orders</h3>
            <p>Quick order processing and delivery.</p>
        </div>
        <div>
            <h3>💳 Flexible Payment</h3>
            <p>Pay on delivery or after confirmation.</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta">
    <h2>Ready to Order?</h2>
    <p>Browse our products and place your order today.</p><br>
    <a href="user/products.php">Order Now</a>
</section>

<!-- FOOTER -->
<footer>
    © <?php echo date("Y"); ?> Electronics Ordering System | Powered by Mwakyembe
</footer>

</body>
</html>