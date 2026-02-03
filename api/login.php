<?php
session_start();
include("../config/db.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Get user from database
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password'])){
        // Set session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = ($user['role_id'] == 1) ? 'admin' : 'customer';

        // Redirect based on role
        if($_SESSION['role'] == 'admin'){
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../customer/products.php");
        }
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!-- Simple Login Form -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>
body{font-family:Arial,sans-serif;background:#f4f4f4;}
.login-container{width:400px;margin:100px auto;padding:30px;background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);}
input{width:100%;padding:10px;margin:10px 0;border-radius:5px;border:1px solid #ccc;}
button{width:100%;padding:10px;background:#007bff;color:#fff;border:none;border-radius:5px;cursor:pointer;}
button:hover{background:#0056b3;}
.error{color:red;}
</style>
</head>
<body>
<div class="login-container">
<h2>Login</h2>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
<form method="post" action="">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>
</div>
</body>
</html>