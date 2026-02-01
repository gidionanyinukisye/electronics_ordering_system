<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config/db.php");

// 1️⃣ Hakikisha user ame-login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2️⃣ Pata orders za customer aliye-login
$sql = "SELECT order_id, order_date, status
        FROM orders
        WHERE user_id = ?
        ORDER BY order_date DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">My Orders</h3>

    <?php if(mysqli_num_rows($result) == 0): ?>
        <div class="alert alert-info">
            You have not placed any orders yet.
        </div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#Order ID</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['order_id']); ?></td>
                    <td><?= htmlspecialchars($row['order_date']); ?></td>
                    <td>
                        <span class="badge 
                            <?= $row['status'] == 'Pending' ? 'bg-warning' : 'bg-success'; ?>">
                            <?= htmlspecialchars($row['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="order_details.php?id=<?= $row['order_id']; ?>" 
                           class="btn btn-sm btn-primary">
                           View
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

</body>
</html>