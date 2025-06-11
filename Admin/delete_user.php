<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ../Auth/login.php");
    exit;
}

include "../Conf/database.php";

$user_id = $_GET['id'] ?? null;

if ($user_id) {
    try {
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);

        $stmt_fetch_username = $db->prepare("SELECT username FROM users WHERE id = ?");
        $stmt_fetch_username->execute([$user_id]);
        $deleted_username = $stmt_fetch_username->fetchColumn();

        $log_message = $_SESSION['username'] . " (admin) Menghapus user: " . $deleted_username . " (ID: " . $user_id . ")";
        $log_stmt = $db->prepare("INSERT INTO log (isi_log, tanggal_log, id_user) VALUES (?, NOW(), ?)");
        $log_stmt->execute([$log_message, $_SESSION['user_id']]);

        $_SESSION['success'] = "User berhasil dihapus!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

header("Location: user.php");
exit;
?>