<?php
session_start();
require_once "../config/db.php";

/* Admin only */
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../public/login.html");
    exit();
}

$sql = "
SELECT 
    p.product_id,
    p.product_name,
    p.price,
    p.stock,
    p.image,
    c.category_name
FROM products p
JOIN categories c ON p.category_id = c.category_id
ORDER BY p.product_id DESC
";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Products</h3>
    <a href="add_product.php" class="btn btn-primary mb-3">Add Product</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>
                    <img src="../uploads/<?= $row['image']; ?>" width="60">
                </td>
                <td><?= htmlspecialchars($row['product_name']); ?></td>
                <td><?= $row['price']; ?></td>
                <td><?= $row['stock']; ?></td>
                <td><?= $row['category_name']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back</a>
</div>

</body>
</html>