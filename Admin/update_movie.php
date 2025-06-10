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

// Get form data
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
    
    // Update movie
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
    
    // Update genres
    $db->prepare("DELETE FROM movie_genre WHERE movie_id = ?")->execute([$id]);
    
    $stmt = $db->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES (?, ?)");
    foreach ($genres as $genreId) {
        $stmt->execute([$id, $genreId]);
    }
    
    $db->commit();
    $_SESSION['success'] = "Movie updated successfully!";
} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['error'] = "Error updating movie: " . $e->getMessage();
}

header("Location: movie.php");
exit;