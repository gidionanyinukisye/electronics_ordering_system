<?php
session_start();
require_once "../config/db.php";

/* Admin only */
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../public/login.html");
    exit();
}

$user_id = (int)($_GET['id'] ?? 0);

/* Get current role */
$res = mysqli_query($conn, "SELECT role_id FROM users WHERE user_id=$user_id");
$user = mysqli_fetch_assoc($res);

if ($user) {
    $new_role = ($user['role_id'] == 1) ? 2 : 1;
    mysqli_query($conn, "UPDATE users SET role_id=$new_role WHERE user_id=$user_id");
}

header("Location: ../admin/users.php");
exit();