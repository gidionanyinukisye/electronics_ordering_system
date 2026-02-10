<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ElectroHub | Electronics Ordering System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/styles.css">

<style>
*{box-sizing:border-box}
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#f4f6fb;
    color:#222;
}
a{text-decoration:none}

/* HEADER */
header{
    background:#0a1a33;
    color:#fff;
    padding:20px 8%;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
header h1{margin:0;font-size:28px}
nav a{
    color:#fff;
    margin-left:20px;
    font-weight:500;
}

/* HERO */
.hero{
    background:linear-gradient(120deg,#0a1a33,#124a9f);
    padding:80px 8%;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:40px;
    color:#fff;
}
.hero h2{font-size:42px;margin-bottom:20px}
.hero p{font-size:18px;line-height:1.6;margin-bottom:35px}
.hero button{
    background:#ffb703;
    border:none;
    padding:16px 40px;
    border-radius:30px;
    font-size:16px;
    cursor:pointer;
    font-weight:600;
}
.hero img{width:100%;max-width:450px}

/* SEARCH */
.search-box{
    margin-top:30px;
}
.search-box input{
    width:100%;
    padding:14px 20px;
    border-radius:30px;
    border:none;
    font-size:16px;
}

/* SECTIONS */
.section{
    padding:70px 8%;
}
.section h2{
    text-align:center;
    font-size:34px;
}
.section p{
    text-align:center;
    color:#666;
    margin-bottom:50px;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
    gap:30px;
}

/* CATEGORY CARD */
.card{
    background:#fff;
    border-radius:20px;
    padding:25px;
    text-align:center;
    box-shadow:0 12px 35px rgba(0,0,0,.1);
    transition:.3s;
}
.card:hover{
    transform:translateY(-8px);
}
.card img{
    width:100%;
    height:160px;
    object-fit:contain;
    margin-bottom:15px;
}
.card h3{margin:0}

/* FOOTER */
footer{
    background:#0a1a33;
    color:#ccc;
    padding:40px 8%;
}
.footer-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
}
footer h4{color:#fff}
footer a{color:#ccc;display:block;margin-bottom:8px}
.social a{
    display:inline-block;
    margin-right:15px;
    font-size:18px;
}
.copy{
    text-align:center;
    margin-top:30px;
    font-size:14px;
}

/* RESPONSIVE */
@media(max-width:900px){
    .hero{grid-template-columns:1fr;text-align:center}
}
</style>
</head>

<body>

<!-- HEADER -->
<header>
    <h1>ElectroHub</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="public/login.html">Login</a>
        <a href="public/register.html">Register</a>
    </nav>
</header>

<!-- HERO -->
<section class="hero">
    <div>
        <h2>Order Genuine Electronics With Confidence</h2>
        <p>
            Smartphones, Laptops, TVs, Audio Systems, Home & Office Electronics.
            Pay on delivery after receiving your product.
        </p>

        <div class="search-box">
            <input type="text" placeholder="Search electronics products...">
        </div>

        <br>
        <button onclick="location.href='public/login.html'">Start Ordering</button>
    </div>

    <img src="assets/images/electronics.jpg" alt="Electronics">
</section>

<!-- CATEGORIES -->
<section class="section">
    <h2>Popular Categories</h2>
    <p>Explore our wide range of electronics</p>

    <div class="grid">
        <div class="card">
            <img src="assets/images/phones.jpg">
            <h3>Smartphones</h3>
        </div>

        <div class="card">
            <img src="assets/images/laptops.jpg">
            <h3>Laptops & Computers</h3>
        </div>

        <div class="card">
            <img src="assets/images/tv.jpg">
            <h3>Televisions</h3>
        </div>

        <div class="card">
            <img src="assets/images/audio.jpg">
            <h3>Audio Systems</h3>
        </div>

        <div class="card">
            <img src="assets/images/accessories.jpg">
            <h3>Accessories</h3>
        </div>

        <div class="card">
            <img src="assets/images/home.jpg">
            <h3>Home Electronics</h3>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-grid">
        <div>
            <h4>About ElectroHub</h4>
            <p>Trusted electronics ordering system providing genuine products with safe delivery.</p>
        </div>

        <div>
            <h4>Quick Links</h4>
            <a href="index.php">Home</a>
            <a href="public/login.html">Login</a>
            <a href="public/register.html">Register</a>
        </div>

        <div>
            <h4>Contact Us</h4>
            <p>📍 Tanzania</p>
            <p>📞 +255 671668284</p>
            <p>✉️ info@electrohub.co.tz</p>
        </div>

        <div class="social">
            <h4>Follow Us</h4>
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
            <a href="#">WhatsApp</a>
        </div>
    </div>

    <div class="copy">
        &copy; 2026 ElectroHub – Electronics Ordering System
    </div>
</footer>

</body>
</html>