<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$response = array('success' => false, 'message' => '', 'redirect' => '');

if(isset($_POST['email']) && isset($_POST['password'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
    if(mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);
        if($user['status'] != 'active'){
            $response['message'] = 'Your account is blocked!';
        } elseif(password_verify($password, $user['password'])){
            $_SESSION['auth'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'user';
            $_SESSION['role_id'] = isset($user['role_id']) ? $user['role_id'] : 2;
            $response['success'] = true;
            $response['message'] = 'Login successful!';
            // Redirect based on role: admin (1) or user (2)
            $response['redirect'] = ($_SESSION['role_id'] == 1) ? '../admin/dashboard.php' : '../user/dashboard.php';
        } else {
            $response['message'] = 'Incorrect password!';
        }
    } else {
        $response['message'] = 'Email not registered!';
    }
} else {
    $response['message'] = 'Missing email or password!';
}

header('Content-Type: application/json');
echo json_encode($response);
?>