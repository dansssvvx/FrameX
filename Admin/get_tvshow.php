<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
include "../Conf/database.php";

if (!isset($_GET['id'])) {
    die(json_encode(['error' => 'ID tidak valid']));
}

$id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM custom_tv_show WHERE id = ?");
$stmt->execute([$id]);
$tvshow = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tvshow) {
    die(json_encode(['error' => 'TV Show tidak ditemukan']));
}

$stmt = $db->prepare(query: "SELECT genre_id FROM tvshow_genre WHERE tvshow_id = ?");
$stmt->execute([$id]);
$genres = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

$tvshow['genres'] = $genres;
header('Content-Type: application/json');
echo json_encode($tvshow);
?>