<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../public/login.html");
    exit();
}

include("../config/db.php");

/* Fetch products + category name */
$sql = "
    SELECT 
        p.product_id,
        p.product_name,
        p.description,
        p.price,
        p.stock,
        p.created_at,
        c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    ORDER BY p.product_id DESC
";

$result = mysqli_query($conn, $sql);

if(!$result){
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin | Manage Products</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{ background:#f4f6f9; }
.sidebar{
    width:220px;
    height:100vh;
    position:fixed;
    background:#1e40af;
    color:#fff;
    padding:20px;
}
.sidebar a{
    display:block;
    color:#fff;
    text-decoration:none;
    margin:10px 0;
}
.sidebar a:hover{ text-decoration:underline; }
.main{
    margin-left:240px;
    padding:20px;
}
</style>
</head>

<body>

<div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="dashboard.php">Dashboard</a>
    <a href="products.php">Manage Products</a>
    <a href="orders.php">View Orders</a>
    <a href="users.php">Manage Users</a>
    <a href="../api/logout.php">Logout</a>
</div>

<div class="main">
    <h2>Manage Products</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price ($)</th>
                <th>Stock</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['product_id']; ?></td>
                <td><?= $row['product_name']; ?></td>
                <td><?= $row['category_name'] ?? 'N/A'; ?></td>
                <td><?= $row['description']; ?></td>
                <td><?= $row['price']; ?></td>

                <!-- UPDATE STOCK -->
                <td>
                    <form action="../api/update_stock.php" method="POST" class="d-flex">
                        <input type="hidden" name="product_id" value="<?= $row['product_id']; ?>">
                        <input type="number" name="stock" value="<?= $row['stock']; ?>" class="form-control me-1" required>
                        <button class="btn btn-success btn-sm">Save</button>
                    </form>
                </td>

                <td><?= $row['created_at']; ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $row['product_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="../api/delete_product.php?id=<?= $row['product_id']; ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this product?')">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">No products found</td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>

</body>
</html>