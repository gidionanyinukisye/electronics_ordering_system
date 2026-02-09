<?php
require __DIR__ . '/config/db.php';

if (isset($conn)) {
    // Check procedural mysqli connection errors
    if (function_exists('mysqli_connect_errno') && mysqli_connect_errno()) {
        echo "DB connection failed: " . mysqli_connect_error();
        exit;
    }

    // Check OO mysqli connection errors
    if (is_object($conn) && property_exists($conn, 'connect_error') && $conn->connect_error) {
        echo "DB connection failed: " . $conn->connect_error;
        exit;
    }

    echo "DB connected successfully";
} else {
    echo "No $conn variable found. Verify config/db.php";
}

?>
