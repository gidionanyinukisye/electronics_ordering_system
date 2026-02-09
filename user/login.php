<?php
session_start();
include "../db.php"; // connect to database

$message = ''; // variable ya kuonyesha errors au success

if(isset($_POST['login'])){
    // sanitize user input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // check if user exists
    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
    if(mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);

        // check if account is active
        if($user['status'] != 'active'){
            $message = "Your account is blocked. Contact admin.";
        } elseif(password_verify($password, $user['password'])){
            // create session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            
            // redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Email not registered!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | ElectroHub</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{font-family:Segoe UI, sans-serif;background:#f4f6fb;margin:0;padding:0;display:flex;justify-content:center;align-items:center;height:100vh;}
.login-container{background:#fff;padding:40px;border-radius:15px;box-shadow:0 12px 30px rgba(0,0,0,0.1);width:350px;}
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

<div class="login-container">
    <h2>User Login</h2>

    <?php if($message != ''){ echo "<div class='message'>$message</div>"; } ?>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>