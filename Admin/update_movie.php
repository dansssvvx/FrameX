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
$release_date = $_POST['release_date'];
$status = $_POST['status'];
$revenue = $_POST['revenue'];
$homepage = $_POST['homepage'];
$poster_path = $_POST['poster'];
$genres = $_POST['genres'] ?? [];

try {
    $db->beginTransaction();
    
    // 1. Update movie details in custom_movie table first
    $stmt = $db->prepare("UPDATE custom_movie SET 
        title = ?, 
        overview = ?, 
        tagline = ?, 
        release_date = ?, 
        status = ?, 
        revenue = ?, 
        homepage = ?, 
        poster_path = ? 
        WHERE id = ?");
    
    $stmt->execute([
        $title,
        $overview,
        $tagline,
        $release_date,
        $status,
        $revenue,
        $homepage,
        $poster_path,
        $id
    ]);
    
    $db->prepare("DELETE FROM movie_genre WHERE movie_id = ?")->execute([$id]);
    
    if (!empty($genres)) {
        $stmt_genre = $db->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES (?, ?)");
        foreach ($genres as $genreId) {
            $stmt_genre->execute([$id, $genreId]);
        }
    }
    
    $db->commit();

    $log_message = $_SESSION['username'] . " (admin) Mengupdate Movie dengan ID " . $id . ": " . $title;
    $log_stmt = $db->prepare("INSERT INTO log (isi_log, tanggal_log, id_user) VALUES (?, NOW(), ?)");
    $log_stmt->execute([$log_message, $_SESSION['user_id']]);

    $_SESSION['success'] = "Movie updated successfully!";
} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['error'] = "Error updating Movie: " . $e->getMessage();
}

header("Location: movie.php");
exit;
?>