<?php
// ON errors (kwa development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// START SESSION
session_start();

// CONNECT DB
require_once "../config/db.php";

// ALLOW ONLY POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/login.html");
    exit();
}

// GET DATA
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// VALIDATION
if ($email === '' || $password === '') {
    die("Email or Password required");
}

// PREPARED STATEMENT (SECURE)
$sql = "SELECT user_id, full_name, password, role_id 
        FROM users 
        WHERE email = ? 
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// CHECK USER
if (mysqli_num_rows($result) !== 1) {
    die("User not found");
}

$user = mysqli_fetch_assoc($result);

// VERIFY PASSWORD
if (!password_verify($password, $user['password'])) {
    die("Incorrect password");
}

// CREATE SESSION
$_SESSION['user_id']   = $user['user_id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['role_id']   = $user['role_id'];

// ROLE BASED REDIRECT
if ($user['role_id'] == 1) {
    header("Location: ../admin/dashboard.php");
    exit();
} else {
    header("Location: ../user/dashboard.php");
    exit();
}