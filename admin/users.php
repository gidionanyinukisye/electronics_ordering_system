<?php
session_start();
include("../config/db.php");

/* ===== ADMIN AUTH ===== */
if (!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1) {
    header("Location: ../public/login.html");
    exit;
}

/* ===== SEARCH & FILTER ===== */
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

$sql = "SELECT * FROM users WHERE 1";
if ($search != '') {
    $sql .= " AND (full_name LIKE '%$search%' OR email LIKE '%$search%')";
}
if ($status != '') {
    $sql .= " AND status='$status'";
}
$sql .= " ORDER BY user_id DESC";

$users = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin | Manage Users</title>
<style>
body{
    margin:0;
    padding:20px;
    font-family: 'Segoe UI', sans-serif;
    background:#f4f6f9;
}
h1{
    color:#0d6efd;
    margin-bottom:15px;
}

/* Search */
.search-box{
    display:flex;
    gap:10px;
    margin-bottom:15px;
}
.search-box input,
.search-box select{
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
}
.search-box button{
    padding:10px 16px;
    background:#0d6efd;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}
th, td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:center;
}
th{
    background:#0d6efd;
    color:white;
}
tr:hover{
    background:#f1f1f1;
}

/* Status */
.status-active{
    color:#198754;
    font-weight:bold;
}
.status-blocked{
    color:#dc3545;
    font-weight:bold;
}

/* Buttons */
.btn{
    padding:6px 10px;
    border-radius:5px;
    text-decoration:none;
    font-size:13px;
    margin:2px;
    display:inline-block;
}
.block{background:#dc3545;color:white;}
.unblock{background:#198754;color:white;}
.update{background:#ffc107;color:black;}
.orders{background:#6f42c1;color:white;}
</style>
</head>
<body>

<h1>👤 Manage Users</h1>

<!-- SEARCH -->
<form class="search-box" method="GET">
    <input type="text" name="search" placeholder="Search name or email" value="<?= htmlspecialchars($search) ?>">
    <select name="status">
        <option value="">All Status</option>
        <option value="active" <?= $status=='active'?'selected':'' ?>>Active</option>
        <option value="blocked" <?= $status=='blocked'?'selected':'' ?>>Blocked</option>
    </select>
    <button type="submit">Search / Filter</button>
</form>

<!-- TABLE -->
<table>
<tr>
    <th>ID</th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Status</th>
    <th>Date Joined</th>
    <th>Actions</th>
</tr>

<?php if(mysqli_num_rows($users) > 0): ?>
<?php while($u = mysqli_fetch_assoc($users)): ?>
<tr>
    <td><?= $u['user_id'] ?></td>
    <td><?= htmlspecialchars($u['full_name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td>
        <?php if($u['status']=='active'): ?>
            <span class="status-active">ACTIVE</span>
        <?php else: ?>
            <span class="status-blocked">BLOCKED</span>
        <?php endif; ?>
    </td>
    <td><?= date("d M Y", strtotime($u['created_at'])) ?></td>
    <td>
        <?php if($u['status']=='active'): ?>
            <a href="block_users.php?id=<?= $u['user_id'] ?>" class="btn block"
               onclick="return confirm('Block this user?')">Block</a>
        <?php else: ?>
            <a href="unblock_users.php?id=<?= $u['user_id'] ?>" class="btn unblock"
               onclick="return confirm('Unblock this user?')">Unblock</a>
        <?php endif; ?>

        <a href="update_user.php?id=<?= $u['user_id'] ?>" class="btn update">Update</a>
        <a href="view_orders.php?user_id=<?= $u['user_id'] ?>" class="btn orders">Orders</a>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="6">No users found</td></tr>
<?php endif; ?>
</table>

</body>
</html>