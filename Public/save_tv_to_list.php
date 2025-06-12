<?php
session_start();
require_once '../Conf/database.php';
include_once "../Conf/info.php"; // Include info.php for $apikey

header('Content-Type: application/json');

if (!isset($_SESSION['user_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $tv_show_id = filter_input(INPUT_POST, 'tv_show_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING); // 'add', 'edit', or 'remove'

    if (!$tv_show_id || !in_array($action, ['add', 'edit', 'remove'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid parameters.']);
        exit;
    }

    try {
        if ($action === 'remove') {
            $stmt = $db->prepare("DELETE FROM user_tv_list WHERE user_id = ? AND tv_show_id = ?");
            $stmt->execute([$user_id, $tv_show_id]);
            echo json_encode(['success' => true, 'message' => 'TV Show removed from your list.']);
            exit; // Exit after remove action
        }

        // For 'add' or 'edit' actions, get modal data
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        $current_episode = filter_input(INPUT_POST, 'current_episode', FILTER_VALIDATE_INT) ?? 0;
        $current_season = filter_input(INPUT_POST, 'current_season', FILTER_VALIDATE_INT) ?? 0;

        // Check if TV show exists in TMDB and get total episodes/seasons
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://api.themoviedb.org/3/tv/{$tv_show_id}?api_key=" . $apikey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => ["Accept: application/json"],
        ]);
        $response_tv_details = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err || !$response_tv_details) {
            echo json_encode(['success' => false, 'message' => 'Could not fetch TV show details from API.']);
            exit;
        }
        $tv_details = json_decode($response_tv_details);
        $total_episodes_api = $tv_details->number_of_episodes ?? 0;
        $total_seasons_api = $tv_details->number_of_seasons ?? 0;

        // Ensure current episode/season don't exceed total from API
        $current_episode = min($current_episode, $total_episodes_api);
        $current_season = min($current_season, $total_seasons_api);


        // Check if it's already in the list to decide between INSERT or UPDATE
        $stmt_check_exist = $db->prepare("SELECT COUNT(*) FROM user_tv_list WHERE user_id = ? AND tv_show_id = ?");
        $stmt_check_exist->execute([$user_id, $tv_show_id]);
        $exists = ($stmt_check_exist->fetchColumn() > 0);

        if ($exists) {
            // Update existing entry
            $stmt = $db->prepare("UPDATE user_tv_list SET status = ?, current_episode = ?, current_season = ? WHERE user_id = ? AND tv_show_id = ?");
            $stmt->execute([$status, $current_episode, $current_season, $user_id, $tv_show_id]);
            echo json_encode(['success' => true, 'message' => 'TV Show entry updated successfully.']);
        } else {
            // Insert new entry
            $stmt = $db->prepare("INSERT INTO user_tv_list (user_id, tv_show_id, status, current_episode, current_season, total_episodes, total_seasons) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $tv_show_id, $status, $current_episode, $current_season, $total_episodes_api, $total_seasons_api]);
            echo json_encode(['success' => true, 'message' => 'TV Show added to your list.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}