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
$username = $_SESSION['username'] ?? 'User';

/* =========================
   FETCH STATISTICS
========================= */
$total_orders = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) total FROM orders WHERE user_id=$user_id")
)['total'];

$pending_orders = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) total FROM orders WHERE user_id=$user_id AND status='Pending'")
)['total'];

$delivered_orders = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) total FROM orders WHERE user_id=$user_id AND status='Delivered'")
)['total'];

$cart_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{box-sizing:border-box}
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:linear-gradient(120deg,#0a1a33,#124a9f);
    color:#fff;
}
.header{
    padding:25px 8%;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.header h2{margin:0}
.header a{
    color:#fff;
    text-decoration:none;
    margin-left:20px;
    font-weight:500;
}
.container{
    padding:40px 8%;
}
.welcome{
    margin-bottom:30px;
}
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
    gap:25px;
}
.card{
    background:#fff;
    color:#222;
    padding:25px;
    border-radius:18px;
    box-shadow:0 12px 35px rgba(0,0,0,.15);
    transition:.3s;
}
.card:hover{
    transform:translateY(-8px);
}
.card h3{
    margin:0;
    font-size:30px;
}
.card p{
    margin:10px 0 0;
    color:#666;
}
.icon{
    font-size:35px;
    margin-bottom:10px;
}
.links{
    margin-top:45px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:20px;
}
.link-card{
    background:rgba(255,255,255,.12);
    padding:25px;
    border-radius:18px;
    text-align:center;
    transition:.3s;
}
.link-card:hover{
    background:rgba(255,255,255,.22);
}
.link-card a{
    color:#fff;
    text-decoration:none;
    font-size:18px;
    display:block;
}
.footer{
    text-align:center;
    padding:25px;
    opacity:.8;
}
@media(max-width:768px){
    .header{flex-direction:column;gap:15px}
}
</style>
</head>

<body>

<div class="header">
    <h2>ElectroHub</h2>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Cart (<?php echo $cart_items; ?>)</a>
        <a href="my_order.php">My Orders</a>
        <a href="../public/logout.php">Logout</a>
    </nav>
</div>

<div class="container">

    <div class="welcome">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?> 👋</h1>
        <p>Manage your electronics orders easily from your dashboard</p>
    </div>

    <!-- STAT CARDS -->
    <div class="cards">
        <div class="card">
            <div class="icon">📦</div>
            <h3><?php echo $total_orders; ?></h3>
            <p>Total Orders</p>
        </div>

        <div class="card">
            <div class="icon">⏳</div>
            <h3><?php echo $pending_orders; ?></h3>
            <p>Pending Orders</p>
        </div>

        <div class="card">
            <div class="icon">✅</div>
            <h3><?php echo $delivered_orders; ?></h3>
            <p>Delivered Orders</p>
        </div>

        <div class="card">
            <div class="icon">🛒</div>
            <h3><?php echo $cart_items; ?></h3>
            <p>Items in Cart</p>
        </div>
    </div>

    <!-- QUICK LINKS -->
    <div class="links">
        <div class="link-card">
            <a href="products.php">🛍 Browse Products</a>
        </div>
        <div class="link-card">
            <a href="cart.php">🛒 View Cart</a>
        </div>
        <div class="link-card">
            <a href="my_order.php">📄 Order History</a>
        </div>
        <div class="link-card">
            <a href="../public/logout.php">🚪 Logout</a>
        </div>
    </div>

</div>

<div class="footer">
    &copy; 2026 ElectroHub – Electronics Ordering System
</div>

</body>
</html>