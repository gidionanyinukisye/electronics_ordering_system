<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: ../public/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "SELECT full_name FROM users WHERE user_id=$user_id");
$user = mysqli_fetch_assoc($query);
$full_name = $user['full_name'] ?? 'Customer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .dashboard-title {
            margin-top: 30px;
            margin-bottom: 30px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="dashboard-title">Welcome, <?= htmlspecialchars($full_name); ?> (Customer)</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="products.php" class="text-decoration-none">
                <div class="card p-4 text-center bg-primary text-white">
                    <h5>View Products</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="my_orders.php" class="text-decoration-none">
                <div class="card p-4 text-center bg-success text-white">
                    <h5>My Orders</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="../api/logout.php" class="text-decoration-none">
                <div class="card p-4 text-center bg-danger text-white">
                    <h5>Logout</h5>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>