<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ./login.php?error=access_denied");
    exit;
}

include "../Conf/database.php";

$title = $_POST['title'];
$overview = $_POST['overview'];
$tagline = $_POST['tagline'] ?? '';
$first_air_date = $_POST['first_air_date'] ?? '';
$status = $_POST['status'];
$total_episodes = $_POST['total_episodes'] ?? 0;
$total_seasons = $_POST['total_seasons'] ?? 0;
$homepage = $_POST['homepage'] ?? '';
$poster = $_POST['poster'] ?? '';
$genres = $_POST['genres'] ?? [];

if (empty($title) || empty($overview) || empty($first_air_date) || empty($status)) {
    $_SESSION['error'] = "Harap isi semua field wajib!";
    header("Location: tv.php");
    exit;
}

try {
    $db->beginTransaction();

    $sql = "INSERT INTO custom_tv_show (
        title, 
        overview, 
        tagline, 
        first_air_date, 
        status, 
        total_episodes,
        total_seasons, 
        homepage, 
        poster_path
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $title,
        $overview,
        $tagline,
        $first_air_date,
        $status,
        $total_episodes,
        $total_seasons,
        $homepage,
        $poster
    ]);
    
    $tvshow_id = $db->lastInsertId();
    
    if (!empty($genres)) {
        $valid_genres = array_filter($genres, 'is_numeric');
        
        if (!empty($valid_genres)) {
            $sql = "INSERT INTO tvshow_genre (tvshow_id, genre_id) VALUES ";
            $placeholders = [];
            $values = [];
            
            foreach ($valid_genres as $genre_id) {
                $placeholders[] = "(?, ?)";
                $values[] = $tvshow_id;
                $values[] = (int)$genre_id;
            }
            
            $sql .= implode(", ", $placeholders);
            $stmt = $db->prepare($sql);
            $stmt->execute($values);
        }
    }
    
    $db->commit();
    
    $_SESSION['success'] = "TV Show berhasil ditambahkan!";
    header("Location: tv.php");
    exit;

} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: tv.php");
    exit;
}