<?php
session_start();

// Destroy all user session
session_unset();
session_destroy();

// Redirect back to login page
header("Location: ../public/login.html");
exit;
?>