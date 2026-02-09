<?php
require_once __DIR__ . '/../config/db.php';

$response = array('success' => false, 'message' => '');

if(isset($_POST['full_name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if($password !== $confirm_password){
        $response['message'] = 'Passwords do not match!';
    } else {
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
        if(mysqli_num_rows($check) > 0){
            $response['message'] = 'Email already registered!';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            // Try with role_id first, fallback to just basic columns if table doesn't have role_id
            $insert_result = mysqli_query($conn, "INSERT INTO users(full_name,email,password,status,role_id) VALUES('$full_name','$email','$hashed','active',2)");
            
            if(!$insert_result){
                // If role_id doesn't exist, try without it
                $insert_result = mysqli_query($conn, "INSERT INTO users(full_name,email,password,status) VALUES('$full_name','$email','$hashed','active')");
            }
            
            if($insert_result){
                $response['success'] = true;
                $response['message'] = 'Registration successful! Redirecting to login...';
            } else {
                $response['message'] = 'Registration failed! ' . mysqli_error($conn);
            }
        }
    }
} else {
    $response['message'] = 'Missing required fields!';

    // Log debug information to help diagnose missing POST data
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0777, true);
    }
    $logFile = $logDir . '/api_requests.log';
    $debug = [
        'time' => date('c'),
        'script' => __FILE__,
        'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
        'post' => $_POST,
        'raw_input' => file_get_contents('php://input')
    ];
    @file_put_contents($logFile, json_encode($debug) . PHP_EOL, FILE_APPEND | LOCK_EX);
}

header('Content-Type: application/json');
echo json_encode($response);
?>