<?php
session_start();
include "../db.php"; // Database connection

$message = '';

if(isset($_POST['register'])){
    // sanitize input
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // check password match
    if($password !== $confirm_password){
        $message = "Passwords do not match!";
    } else {
        // check if email already exists
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
        if(mysqli_num_rows($check) > 0){
            $message = "Email already registered!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO users(full_name,email,password,status) VALUES('$full_name','$email','$hashed','active')");
            $message = "Registration successful! <a href='login.php'>Login here</a>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | ElectroHub</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{font-family:Segoe UI, sans-serif;background:#f4f6fb;margin:0;padding:0;display:flex;justify-content:center;align-items:center;height:100vh;}
.register-container{background:#fff;padding:40px;border-radius:15px;box-shadow:0 12px 30px rgba(0,0,0,0.1);width:350px;}
h2{text-align:center;margin-bottom:20px;color:#0a1a33;}
input{width:100%;padding:12px;margin:8px 0;border-radius:8px;border:1px solid #ccc;}
button{width:100%;padding:12px;background:#0a1a33;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:bold;}
button:hover{background:#124a9f;}
.message{color:red;text-align:center;margin-bottom:10px;font-size:14px;}
p{text-align:center;font-size:14px;margin-top:15px;}
a{color:#124a9f;text-decoration:none;}
a:hover{text-decoration:underline;}
</style>
</head>
<body>

<div class="register-container">
    <h2>User Registration</h2>

    <?php if($message != ''){ echo "<div class='message'>$message</div>"; } ?>

    <form method="POST" action="">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>