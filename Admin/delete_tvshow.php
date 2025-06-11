<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ../Auth/login.php");
    exit;
}

include "../Conf/database.php";

$tvshow_id = $_GET['id'] ?? null;

if ($tvshow_id) {
    try {
        $db->beginTransaction();

        // Fetch TV show title for logging before deletion
        $stmt_fetch_title = $db->prepare("SELECT title FROM custom_tv_show WHERE id = ?");
        $stmt_fetch_title->execute([$tvshow_id]);
        $deleted_tvshow_title = $stmt_fetch_title->fetchColumn();

        $stmt = $db->prepare("DELETE FROM custom_tv_show WHERE id = ?");
        $stmt->execute([$tvshow_id]);

        $db->commit();

        // Log the action
        $log_message = $_SESSION['username'] . " (admin) Menghapus TV Show: " . $deleted_tvshow_title . " (ID: " . $tvshow_id . ")";
        $log_stmt = $db->prepare("INSERT INTO log (isi_log, tanggal_log, id_user) VALUES (?, NOW(), ?)");
        $log_stmt->execute([$log_message, $_SESSION['user_id']]);

        $_SESSION['success'] = "TV Show berhasil dihapus!";
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ID TV Show tidak valid!";
}

header("Location: tv.php");
exit;
?>