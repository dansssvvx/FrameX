<?php
require __DIR__ . '/database.php';

try {
    $stmt = $db->query("SELECT COUNT(*) FROM movies");
    $count = $stmt->fetchColumn();
    echo "<br>Terdapat $count film dalam database.";
} catch (PDOException $e) {
    echo "<br>Error query: " . $e->getMessage();
}
?>