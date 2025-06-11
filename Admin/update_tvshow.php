<?php
session_start();
require "../Conf/database.php";

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ./login.php?error=access_denied");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method");
}

$id = $_POST['id'];
$title = $_POST['title'];
$overview = $_POST['overview'];
$tagline = $_POST['tagline'];
$first_air_date = $_POST['first_air_date']; // Correct field name for TV show
$status = $_POST['status'];
$total_episodes = $_POST['total_episodes']; // Correct field name for TV show
$total_seasons = $_POST['total_seasons']; // Correct field name for TV show
$homepage = $_POST['homepage'];
$poster_path = $_POST['poster'];
$genres = $_POST['genres'] ?? [];

try {
    $db->beginTransaction();

    $stmt = $db->prepare("UPDATE custom_tv_show SET
        title = ?,
        overview = ?,
        tagline = ?,
        first_air_date = ?,
        status = ?,
        total_episodes = ?,
        total_seasons = ?,
        homepage = ?,
        poster_path = ?
        WHERE id = ?");

    $stmt->execute([
        $title,
        $overview,
        $tagline,
        $first_air_date,
        $status,
        $total_episodes,
        $total_seasons,
        $homepage,
        $poster_path,
        $id
    ]);

    // Update genres
    $db->prepare("DELETE FROM tvshow_genre WHERE tvshow_id = ?")->execute([$id]);

    $stmt = $db->prepare("INSERT INTO tvshow_genre (tvshow_id, genre_id) VALUES (?, ?)");
    foreach ($genres as $genreId) {
        $stmt->execute([$id, $genreId]);
    }

    $db->commit();
    $log_message = $_SESSION['username'] . " (admin) Mengupdate TV Show dengan ID " . $id . ": " . $title;
    $log_stmt = $db->prepare("INSERT INTO log (isi_log, tanggal_log, id_user) VALUES (?, NOW(), ?)");
    $log_stmt->execute([$log_message, $_SESSION['user_id']]);

    $_SESSION['success'] = "TV Show updated successfully!";
} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['error'] = "Error updating TV Show: " . $e->getMessage();
}

header("Location: tv.php");
exit;
?>