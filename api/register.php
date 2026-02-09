<?php
require_once __DIR__ . '/../config/db.php';

if(isset($_POST['register'])){
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if($password !== $confirm_password){
        echo "<script>alert('Passwords do not match!'); window.location='../public/register.html';</script>";
    } else {
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
        if(mysqli_num_rows($check) > 0){
            echo "<script>alert('Email already registered!'); window.location='../public/register.html';</script>";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO users(full_name,email,password,status) VALUES('$full_name','$email','$hashed','active')");
            echo "<script>alert('Registration successful! Login now.'); window.location='../public/login.html';</script>";
        }
    }
}