<?php
session_start();
require_once "../config/db.php";

/* Admin only */
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../public/login.html");
    exit();
}

$user_id = (int)($_GET['id'] ?? 0);

/* Prevent deleting self */
if ($user_id != $_SESSION['user_id']) {
    mysqli_query($conn, "DELETE FROM users WHERE user_id=$user_id");
}

header("Location: ../admin/users.php");
exit();