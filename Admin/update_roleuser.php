<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ../Auth/login.php");
    exit;
}

include "../Conf/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method");
}

$user_id = $_POST['user_id'];

try {
    // Dapatkan status role saat ini
    $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $current_role = $stmt->fetchColumn();
    
    // Toggle role (0 menjadi 1, 1 menjadi 0)
    $new_role = $current_role ? 0 : 1;
    
    // Update role
    $stmt = $db->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
    $stmt->execute([$new_role, $user_id]);

    $stmt_username = $db->prepare("SELECT username FROM users WHERE id = ?");
    $stmt_username->execute([$user_id]);
    $target_username = $stmt_username->fetchColumn();
    $log_message = $_SESSION['username'] . " (admin) Mengganti role " . $target_username . " menjadi " . ($new_role ? "Admin" : "User");
    $log_stmt = $db->prepare("INSERT INTO log (isi_log, tanggal_log, id_user) VALUES (?, NOW(), ?)");
    $log_stmt->execute([$log_message, $_SESSION['user_id']]);
    
    $_SESSION['success'] = "Role berhasil diubah!";
} catch (PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
}

header("Location: user.php");
exit;
?>