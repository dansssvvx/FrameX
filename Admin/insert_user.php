<?php
session_start();

// Pastikan user sudah login dan adalah admin
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ../Auth/login.php");
    exit;
}

include "../Conf/database.php"; // Sertakan koneksi database

// Pastikan request method adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method");
}

// Ambil data dari form
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);
$is_admin = (int)$_POST['is_admin']; // Pastikan ini integer (0 atau 1)

// Validasi input
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['error'] = "Semua field wajib diisi!";
    header("Location: user.php");
    exit;
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = "Konfirmasi password tidak cocok!";
    header("Location: user.php");
    exit;
}

if (strlen($password) < 8) {
    $_SESSION['error'] = "Password minimal 8 karakter!";
    header("Location: user.php");
    exit;
}

try {
    // Cek apakah username atau email sudah terdaftar
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error'] = "Username atau email sudah terdaftar.";
        header("Location: user.php");
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Masukkan user baru ke database
    $stmt = $db->prepare("INSERT INTO users (username, email, password, is_admin, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$username, $email, $hashed_password, $is_admin]);

    $_SESSION['success'] = "User berhasil ditambahkan!";
    header("Location: user.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: user.php");
    exit;
}
?>