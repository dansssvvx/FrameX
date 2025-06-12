<?php
session_start();
require_once '../Conf/database.php';
// info.php is not strictly needed here as we don't fetch full movie details to save for movies,
// but it's good practice to include if using API keys in general.
// include_once "../Conf/info.php"; 

header('Content-Type: application/json');

if (!isset($_SESSION['user_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
    $movie_type = filter_input(INPUT_POST, 'movie_type', FILTER_SANITIZE_STRING); // 'api_movie' or 'custom_movie'
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING); // 'add', 'edit', or 'remove'

    if (!$movie_id || !in_array($movie_type, ['api_movie', 'custom_movie']) || !in_array($action, ['add', 'edit', 'remove'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid parameters.']);
        exit;
    }

    try {
        if ($action === 'remove') {
            $stmt = $db->prepare("DELETE FROM user_movies WHERE user_id = ? AND movie_id = ? AND movie_type = ?");
            $stmt->execute([$user_id, $movie_id, $movie_type]);
            echo json_encode(['success' => true, 'message' => 'Movie removed from your list.']);
            exit; // Exit after remove action
        }

        // For 'add' or 'edit' actions
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        
        // Validate status against enum values
        $valid_statuses = ['plan to watch', 'watching', 'ended', 'dropped', 'on-hold'];
        if (!in_array($status, $valid_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Invalid status provided.']);
            exit;
        }

        // Check if it's already in the list to decide between INSERT or UPDATE
        $stmt_check_exist = $db->prepare("SELECT COUNT(*) FROM user_movies WHERE user_id = ? AND movie_id = ? AND movie_type = ?");
        $stmt_check_exist->execute([$user_id, $movie_id, $movie_type]);
        $exists = ($stmt_check_exist->fetchColumn() > 0);

        if ($exists) {
            // Update existing entry
            $stmt = $db->prepare("UPDATE user_movies SET status = ? WHERE user_id = ? AND movie_id = ? AND movie_type = ?");
            $stmt->execute([$status, $user_id, $movie_id, $movie_type]);
            echo json_encode(['success' => true, 'message' => 'Movie entry updated successfully.']);
        } else {
            // Insert new entry
            $stmt = $db->prepare("INSERT INTO user_movies (user_id, movie_id, status, movie_type) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $movie_id, $status, $movie_type]);
            echo json_encode(['success' => true, 'message' => 'Movie added to your list.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}