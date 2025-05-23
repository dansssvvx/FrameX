<?php
header('Content-Type: application/json'); // Memberitahu browser bahwa responsnya adalah JSON
header('Access-Control-Allow-Origin: *'); // Mengizinkan semua domain untuk mengakses (untuk pengembangan)
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS'); // Metode yang diizinkan
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); // Headers yang diizinkan

// Konfigurasi Database
$servername = "localhost";
$username = "root"; // Username default XAMPP
$password = "";     // Password default XAMPP (kosong)
$dbname = "frame_x_db";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(["message" => "Koneksi database gagal: " . $conn->connect_error]));
}

// Ambil metode request (GET, POST, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Mengambil semua film
        $sql = "SELECT id, title, release_year, director, genre FROM films ORDER BY created_at DESC";
        $result = $conn->query($sql);

        $films = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $films[] = $row;
            }
        }
        echo json_encode($films);
        break;

    case 'POST':
        // Menambahkan film baru
        $data = json_decode(file_get_contents("php://input"), true);
        $title = $conn->real_escape_string($data['title']);
        $release_year = (int)$data['release_year'];
        $director = $conn->real_escape_string($data['director']);
        $genre = $conn->real_escape_string($data['genre']);

        $sql = "INSERT INTO films (title, release_year, director, genre) VALUES ('$title', $release_year, '$director', '$genre')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Film berhasil ditambahkan", "id" => $conn->insert_id]);
        } else {
            echo json_encode(["message" => "Error: " . $sql . "<br>" . $conn->error]);
        }
        break;

    case 'DELETE':
        // Menghapus film berdasarkan ID
        $data = json_decode(file_get_contents("php://input"), true);
        $id = (int)$data['id'];

        $sql = "DELETE FROM films WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            if ($conn->affected_rows > 0) {
                echo json_encode(["message" => "Film berhasil dihapus"]);
            } else {
                echo json_encode(["message" => "Film tidak ditemukan"]);
            }
        } else {
            echo json_encode(["message" => "Error: " . $sql . "<br>" . $conn->error]);
        }
        break;

    case 'OPTIONS':
        // Handle preflight requests (untuk CORS)
        http_response_code(200);
        break;

    default:
        // Metode tidak diizinkan
        http_response_code(405); // Method Not Allowed
        echo json_encode(["message" => "Metode request tidak diizinkan"]);
        break;
}

$conn->close();
?>