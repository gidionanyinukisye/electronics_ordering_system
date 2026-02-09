<?php
session_start();
include("../config/db.php");

/* ===== ADMIN AUTH ===== */
if (!isset($_SESSION['auth']) || $_SESSION['role_id'] != 1) {
    header("Location: ../public/login.html");
    exit;
}

/* ===== GET USER ===== */
if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = intval($_GET['id']);
$user = mysqli_query($conn, "SELECT * FROM users WHERE user_id=$user_id");
$data = mysqli_fetch_assoc($user);

if (!$data) {
    header("Location: users.php");
    exit;
}

/* ===== UPDATE USER ===== */
if (isset($_POST['update_user'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $status    = $_POST['status'];

    mysqli_query($conn, "
        UPDATE users 
        SET full_name='$full_name', email='$email', status='$status'
        WHERE user_id=$user_id
    ");

    header("Location: users.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin | Update User</title>
<style>
body{
    background:#f4f6f9;
    font-family:'Segoe UI', sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.form-box{
    background:white;
    width:420px;
    padding:25px;
    border-radius:10px;
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}
h2{
    text-align:center;
    color:#0d6efd;
    margin-bottom:20px;
}
label{
    font-weight:600;
}
input, select{
    width:100%;
    padding:10px;
    margin-top:6px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:6px;
}
button{
    width:100%;
    padding:12px;
    background:#0d6efd;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
}
button:hover{
    background:#084298;
}
.back{
    margin-top:10px;
    text-align:center;
}
.back a{
    text-decoration:none;
    color:#6c757d;
}
</style>
</head>
<body>

<div class="form-box">
    <h2>✏️ Update User</h2>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="full_name" required value="<?= htmlspecialchars($data['full_name']) ?>">

        <label>Email</label>
        <input type="email" name="email" required value="<?= htmlspecialchars($data['email']) ?>">

        <label>Status</label>
        <select name="status">
            <option value="active" <?= $data['status']=='active'?'selected':'' ?>>Active</option>
            <option value="blocked" <?= $data['status']=='blocked'?'selected':'' ?>>Blocked</option>
        </select>

        <button type="submit" name="update_user">Update User</button>
    </form>

    <div class="back">
        <a href="users.php">← Back to Manage Users</a>
    </div>
</div>

</body>
</html>