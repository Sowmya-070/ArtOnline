<?php
include '../config/db.php';
header('Content-Type: application/json');

session_start();
$user_id = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data['buy']) && isset($data['artwork_id'])) {
        $artwork_id = $data['artwork_id'];

        // Check if artwork exists
        $checkArtwork = mysqli_query($conn, "SELECT * FROM artworks WHERE id = '$artwork_id'");
        if (mysqli_num_rows($checkArtwork) == 0) {
            echo json_encode(["success" => false, "message" => "Artwork not found."]);
            exit();
        }

        // Add to purchases
        $sql = "INSERT INTO purchases (user_id, artwork_id) VALUES ('$user_id', '$artwork_id')";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["success" => true, "message" => "Artwork purchased successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Purchase failed."]);
        }
        exit();
    }
}
?>
