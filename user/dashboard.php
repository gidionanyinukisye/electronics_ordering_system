<?php
session_start();

// PROTECTION
if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2){
    header("Location: ../public/login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <style>
        body{ font-family: Arial; background:#f4f6f9; padding:20px; }
        .card{ background:#fff; padding:20px; border-radius:10px; }
        a{ display:inline-block; margin-top:10px; color:#fff; background:#e53935; padding:8px 15px; border-radius:5px; text-decoration:none; }
    </style>
</head>
<body>

<div class="card">
    <h2>Customer Dashboard</h2>
    <p>Welcome: <b><?php echo $_SESSION['full_name']; ?></b></p>

    <ul>
        <li>View Products</li>
        <li>Place Order</li>
        <li>My Orders</li>
    </ul>

    <a href="../api/logout.php">Logout</a>
</div>

</body>
</html>