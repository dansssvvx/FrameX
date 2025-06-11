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
$release_date = $_POST['release_date'];
$status = $_POST['status'];
$revenue = $_POST['revenue'] ?? 0;
$homepage = $_POST['homepage'] ?? '';
$poster = $_POST['poster'] ?? '';
$genres = $_POST['genres'] ?? [];

if (empty($title) || empty($overview) || empty($release_date) || empty($status)) {
    $_SESSION['error'] = "Harap isi semua field wajib!";
    header("Location: movie.php");
    exit;
}

try {
    $db->beginTransaction();

    $sql = "INSERT INTO custom_movie (
        title, 
        overview, 
        tagline, 
        release_date, 
        status, 
        revenue, 
        homepage, 
        poster_path
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $title,
        $overview,
        $tagline,
        $release_date,
        $status,
        $revenue,
        $homepage,
        $poster
    ]);
    
    $movie_id = $db->lastInsertId();
    
    if (!empty($genres)) {
        $valid_genres = array_filter($genres, 'is_numeric');
        
        if (!empty($valid_genres)) {
            $sql = "INSERT INTO movie_genre (movie_id, genre_id) VALUES ";
            $placeholders = [];
            $values = [];
            
            foreach ($valid_genres as $genre_id) {
                $placeholders[] = "(?, ?)";
                $values[] = $movie_id;
                $values[] = (int)$genre_id;
            }
            
            $sql .= implode(", ", $placeholders);
            $stmt = $db->prepare($sql);
            $stmt->execute($values);
        }
    }
    
    $db->commit();
    
    $log_message = $_SESSION['username'] . " (admin) menambahkan Movie baru: " . $title;
    $log_stmt = $db->prepare("INSERT INTO log (isi_log, tanggal_log, id_user) VALUES (?, NOW(), ?)");
    $log_stmt->execute([$log_message, $_SESSION['user_id']]);

    $_SESSION['success'] = "Film berhasil ditambahkan!";
    header("Location: movie.php");
    exit;

} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: movie.php");
    exit;
}