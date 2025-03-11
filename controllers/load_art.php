<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

$artist_id = $_SESSION['user_id'];
$sql = "SELECT image FROM artworks WHERE artist_id = $artist_id ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

if ($data) {
    echo json_encode(["status" => "success", "image" => "../uploads/artworks/" . $data['image']]);
} else {
    echo json_encode(["status" => "error", "message" => "No saved artwork found"]);
}
?>
