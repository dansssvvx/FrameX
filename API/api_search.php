<?php
include_once "../Conf/info.php";

// Get search parameters
$query = trim($_GET['query'] ?? '');
$type = $_GET['type'] ?? 'multi'; // 'movie', 'tv', or 'multi'
$page = max(1, intval($_GET['page'] ?? 1)); // Ensure page is at least 1

// Validate type parameter
if (!in_array($type, ['movie', 'tv', 'multi'])) {
    $type = 'multi';
}

if (empty(trim($query))) {
    http_response_code(400);
    echo json_encode(['error' => 'Query parameter is required']);
    exit;
}

$api_url = "https://api.themoviedb.org/3/search/{$type}?api_key={$apikey}&query=" . urlencode($query) . "&page={$page}&include_adult=false";

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => false,
    CURLOPT_HTTPHEADER => ["Accept: application/json"],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch search results']);
    exit;
}

$search_results = json_decode($response);

if (!$search_results) {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid response from API']);
    exit;
}

// Return the search results
header('Content-Type: application/json');
echo json_encode($search_results);
?>