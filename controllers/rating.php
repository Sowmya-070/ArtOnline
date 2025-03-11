<?php
session_start();
include '../config/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_SESSION['user_id'];

if (isset($data['rate_artwork'])) {
    $artwork_id = $data['artwork_id'];
    $rating = $data['rating'];

    $query = "INSERT INTO ratings (user_id, artwork_id, rating) VALUES ('$user_id', '$artwork_id', '$rating') 
              ON DUPLICATE KEY UPDATE rating='$rating'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Rating submitted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to submit rating"]);
    }
}
?>
