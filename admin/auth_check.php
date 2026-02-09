<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true || $_SESSION['role_id'] != 1) {
    header("Location: ../public/login.html");
    exit();
}
?>