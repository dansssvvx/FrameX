<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ../Auth/login.php");
    exit;
}

include "../Conf/database.php";

$movie_id = $_GET['id'] ?? null;

if ($movie_id) {
    try {
        $db->beginTransaction();

        $stmt_fetch_title = $db->prepare("SELECT title FROM custom_movie WHERE id = ?");
        $stmt_fetch_title->execute([$movie_id]);
        $deleted_movie_title = $stmt_fetch_title->fetchColumn();

        $stmt = $db->prepare("DELETE FROM custom_movie WHERE id = ?");
        $stmt->execute([$movie_id]);

        $db->commit();

        // Log the action
        $log_message = $_SESSION['username'] . " (admin) Menghapus movie: " . htmlspecialchars($deleted_movie_title) . " (ID: " . $movie_id . ")";
        $log_stmt = $db->prepare("INSERT INTO log (isi_log, tanggal_log, id_user) VALUES (?, NOW(), ?)");
        $log_stmt->execute([$log_message, $_SESSION['user_id']]);

        $_SESSION['success'] = "Film berhasil dihapus!";
    } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ID film tidak valid!";
}

header("Location: movie.php");
exit;
?>