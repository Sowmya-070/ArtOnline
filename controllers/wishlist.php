<?php
session_start();
include '../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Invalid request or user not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($data['add_wishlist'])) {
    $artwork_id = intval($data['artwork_id']);

    // Check if already exists in wishlist
    $checkQuery = "SELECT * FROM wishlist WHERE user_id = '$user_id' AND artwork_id = '$artwork_id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo json_encode(["success" => false, "message" => "Artwork already in wishlist"]);
        exit();
    }

    // Insert into wishlist
    $insertQuery = "INSERT INTO wishlist (user_id, artwork_id) VALUES ('$user_id', '$artwork_id')";
    if (mysqli_query($conn, $insertQuery)) {
        echo json_encode(["success" => true, "message" => "Added to wishlist"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add to wishlist"]);
    }
    exit();
}

// Handle Remove Wishlist Request
if (isset($data['remove_wishlist'])) {
    $artwork_id = intval($data['artwork_id']);
    $deleteQuery = "DELETE FROM wishlist WHERE user_id = '$user_id' AND artwork_id = '$artwork_id'";

    if (mysqli_query($conn, $deleteQuery)) {
        echo json_encode(["success" => true, "message" => "Removed from wishlist"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to remove from wishlist"]);
    }
    exit();
}

// Invalid Request
echo json_encode(["success" => false, "message" => "Invalid request"]);
exit();
?>
