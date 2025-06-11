<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
include "../Conf/database.php";

if (!isset($_GET['id'])) {
    die(json_encode(['error' => 'ID tidak valid']));
}

$id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM custom_movie WHERE id = ?");
$stmt->execute([$id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    die(json_encode(['error' => 'Movie tidak ditemukan']));
}

$stmt = $db->prepare("SELECT genre_id FROM movie_genre WHERE movie_id = ?");
$stmt->execute([$id]);
$genres = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

$movie['genres'] = $genres;
header('Content-Type: application/json');
echo json_encode($movie);
?>