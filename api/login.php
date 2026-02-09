<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$message = '';

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
    if(mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);
        if($user['status'] != 'active'){
            $message = "Your account is blocked!";
        } elseif(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            header("Location: ../user/dashboard.php");
            exit;
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Email not registered!";
    }
}

if($message != ''){
    echo "<script>alert('$message'); window.location='../public/login.html';</script>";
}