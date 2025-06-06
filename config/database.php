<?php
$host = 'localhost';
$dbname = 'framex';
$username = 'root'; 
$password = '';    

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Koneksi database berhasil!";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>