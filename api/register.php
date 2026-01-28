<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include("../config/db.php");

// STEP 1: Only POST allowed
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: ../public/register.html");
    exit();
}

// STEP 2: Get POST data
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// STEP 3: Validate
if($full_name === '' || $email === '' || $password === ''){
    die("All fields are required");
}

// STEP 4: Check if email already exists
$sql = "SELECT user_id FROM users WHERE email=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){
    die("Email already registered");
}

// STEP 5: Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// STEP 6: Insert user
$insert = "INSERT INTO users (full_name, email, password, role_id) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $insert);
$role_id = 2; // 1=admin, 2=customer
mysqli_stmt_bind_param($stmt, "sssi", $full_name, $email, $hashed_password, $role_id);
$exec = mysqli_stmt_execute($stmt);

if($exec){
    echo "Registration successful! <a href='../public/login.html'>Login Now</a>";
} else {
    die("Registration failed: " . mysqli_error($conn));
}