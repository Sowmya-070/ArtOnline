<?php
session_start();
include '../config/db.php';

// Only return JSON if it's an AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
}

// Get all users for the admin panel
function getAllUsers($conn) {
    return mysqli_query($conn, "SELECT * FROM users");
}

// Get all artworks for the admin panel
function getAllArtworks($conn) {
    return mysqli_query($conn, "SELECT artworks.*, users.name AS artist_name FROM artworks JOIN users ON artworks.artist_id = users.id");
}

// Get reported artworks
function getReportedArtworks($conn) {
    return mysqli_query($conn, "SELECT reported_artworks.*, artworks.title, users.name AS reported_by 
                                FROM reported_artworks 
                                JOIN artworks ON reported_artworks.artwork_id = artworks.id 
                                JOIN users ON reported_artworks.user_id = users.id");
}

// Handle API Requests
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    if (isset($data['delete_user'])) {
        $user_id = intval($data['user_id']);
        $deleteQuery = "DELETE FROM users WHERE id = '$user_id'";
        if (mysqli_query($conn, $deleteQuery)) {
            echo json_encode(["success" => true, "message" => "User deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete user: " . mysqli_error($conn)]);
        }
        exit();
    }

    if (isset($data['delete_artwork'])) {
        $artwork_id = intval($data['artwork_id']);
        $deleteQuery = "DELETE FROM artworks WHERE id = '$artwork_id'";
        if (mysqli_query($conn, $deleteQuery)) {
            echo json_encode(["success" => true, "message" => "Artwork deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete artwork: " . mysqli_error($conn)]);
        }
        exit();
    }

    if (isset($data['delete_reported_artwork'])) {
        $artwork_id = intval($data['artwork_id']);
        $deleteQuery = "DELETE FROM reported_artworks WHERE artwork_id = '$artwork_id'";
        if (mysqli_query($conn, $deleteQuery)) {
            echo json_encode(["success" => true, "message" => "Reported artwork removed!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to remove reported artwork: " . mysqli_error($conn)]);
        }
        exit();
    }

    // Invalid API request
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit();
}
?>
