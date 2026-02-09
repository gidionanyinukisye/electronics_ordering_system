<?php
session_start();
include "../db.php";

$message = '';

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);

    $res = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' LIMIT 1");
    if(mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);
        if($user['status'] != 'active'){
            $message = "Account blocked. Contact admin.";
        } elseif(password_verify($password,$user['password'])){
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
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
<style>
body{font-family:Segoe UI,sans-serif;background:#f4f6fb;margin:0;padding:0;}
.container{max-width:400px;margin:60px auto;background:#fff;padding:25px;border-radius:12px;box-shadow:0 12px 30px rgba(0,0,0,.1);}
h2{text-align:center;margin-bottom:20px;}
input{width:100%;padding:12px;margin:8px 0;border:1px solid #ccc;border-radius:8px;}
button{background:#124a9f;color:#fff;padding:12px;width:100%;border:none;border-radius:8px;cursor:pointer;font-weight:600;}
.message{color:red;text-align:center;margin-bottom:10px;}
a{color:#124a9f;text-decoration:none;}
</style>
</head>
<body>

<div class="container">
<h2>Login</h2>
<?php if($message != ''){ echo '<div class="message">'.$message.'</div>'; } ?>
<form method="post">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="login">Login</button>
</form>
<p style="text-align:center;margin-top:12px;">No account? <a href="register.php">Register</a></p>
</div>

</body>
</html