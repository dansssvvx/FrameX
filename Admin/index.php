<?php
session_start();

// Cek apakah user sudah login DAN memiliki role admin
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /login.php?error=access_denied");
    exit;
}
include "config/info.php";
include "config/database.php";

?>