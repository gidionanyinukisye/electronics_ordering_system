<?php
session_start();
require_once "../config/db.php";

/* Admin only */
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../public/login.html");
    exit();
}

/* Fetch users */
$sql = "SELECT user_id, full_name, email, role_id, created_at FROM users";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin | Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">Manage Users</h3>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['user_id']; ?></td>
                <td><?= htmlspecialchars($row['full_name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td>
                    <?= ($row['role_id'] == 1) ? 'Admin' : 'Customer'; ?>
                </td>
                <td><?= $row['created_at']; ?></td>
                <td>
                    <!-- Change role -->
                    <a href="../api/change_role.php?id=<?= $row['user_id']; ?>"
                       class="btn btn-warning btn-sm"
                       onclick="return confirm('Change role?')">
                        Change Role
                    </a>

                    <!-- Delete user -->
                    <?php if ($row['user_id'] != $_SESSION['user_id']): ?>
                    <a href="../api/delete_user.php?id=<?= $row['user_id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this user?')">
                        Delete
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back</a>
</div>

</body>
</html>