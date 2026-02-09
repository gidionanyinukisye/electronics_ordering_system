<?php
session_start();

// Clear session data
$_SESSION = array();
session_unset();
session_destroy();

// Check if this is an AJAX request or form redirect
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    // Return JSON response for AJAX calls
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Logout successful!',
        'redirect' => '../public/login.html'
    ]);
    exit;
} else {
    // Redirect for direct link clicks
    header("Location: ../public/login.html");
    exit;
}
?>