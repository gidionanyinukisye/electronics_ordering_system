<?php
session_start();
include "../db.php"; // Unganisha database yako

$message = '';

if(isset($_POST['register'])){
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if($password !== $confirm_password){
        $message = "Passwords do not match!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if(mysqli_num_rows($check) > 0){
            $message = "Email already registered!";
        } else {
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
<style>
body{font-family:Segoe UI,sans-serif;background:#f4f6fb;margin:0;padding:0;}
.container{max-width:400px;margin:50px auto;background:#fff;padding:25px;border-radius:12px;box-shadow:0 12px 30px rgba(0,0,0,.1);}
h2{text-align:center;margin-bottom:20px;}
input{width:100%;padding:12px;margin:8px 0;border:1px solid #ccc;border-radius:8px;}
button{background:#124a9f;color:#fff;padding:12px;width:100%;border:none;border-radius:8px;cursor:pointer;font-weight:600;}
.message{color:red;text-align:center;margin-bottom:10px;}
a{color:#124a9f;text-decoration:none;}
</style>
</head>
<body>

<div class="container">
<h2>Create Account</h2>
<?php if($message != ''){ echo '<div class="message">'.$message.'</div>'; } ?>
<form method="post">
<input type="text" name="full_name" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<input type="password" name="confirm_password" placeholder="Confirm Password" required>
<button type="submit" name="register">Register</button>
</form>
<p style="text-align:center;margin-top:12px;">Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>