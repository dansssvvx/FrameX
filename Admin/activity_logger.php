<?php
/**
 * Activity Logger for Admin Dashboard
 * This file provides functions to log admin activities
 */

function logActivity($db, $action, $description = '', $user_id = null, $user_type = 'admin') {
    try {
        $stmt = $db->prepare("INSERT INTO activity_log (action, description, user_id, user_type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$action, $description, $user_id, $user_type]);
        return true;
    } catch (Exception $e) {
        // Silently fail if logging fails - don't break the main functionality
        error_log("Activity logging failed: " . $e->getMessage());
        return false;
    }
}

function getRecentActivities($db, $limit = 5) {
    try {
        $stmt = $db->prepare("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // Return empty array if query fails
        return [];
    }
}

// Auto-log admin login
function logAdminLogin($db, $user_id, $username) {
    logActivity($db, 'Admin Login', "Admin '$username' logged in successfully", $user_id, 'admin');
}

// Auto-log admin logout
function logAdminLogout($db, $user_id, $username) {
    logActivity($db, 'Admin Logout', "Admin '$username' logged out", $user_id, 'admin');
}

// Log movie operations
function logMovieOperation($db, $operation, $movie_title, $user_id) {
    $description = "Movie '$movie_title' was $operation";
    logActivity($db, "Movie $operation", $description, $user_id, 'admin');
}

// Log TV show operations
function logTVShowOperation($db, $operation, $tv_title, $user_id) {
    $description = "TV Show '$tv_title' was $operation";
    logActivity($db, "TV Show $operation", $description, $user_id, 'admin');
}

// Log user management operations
function logUserOperation($db, $operation, $target_user, $user_id) {
    $description = "User '$target_user' was $operation";
    logActivity($db, "User $operation", $description, $user_id, 'admin');
}
?> 