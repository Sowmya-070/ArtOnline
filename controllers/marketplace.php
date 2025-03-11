<?php
session_start();
include '../config/db.php';

// Upload Artwork
if (isset($_POST['upload'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $artist_id = $_SESSION['user_id'];
    $image = ""; // Initialize Image Variable

    // Handle Saved Artwork Selection
    if (!empty($_POST['saved_image'])) {
        $image = $_POST['saved_image']; // Use saved artwork from DB
    }

    // Handle New Image Upload
    if (!empty($_FILES["uploaded_image"]["name"])) {
        $target_dir = "../uploads/artworks/";
        
        // Ensure directory exists
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Get file extension
        $imageFileType = strtolower(pathinfo($_FILES["uploaded_image"]["name"], PATHINFO_EXTENSION));

        // Generate a unique filename
        $image = "art_" . time() . "_" . rand(1000, 9999) . "." . $imageFileType;
        $target_file = $target_dir . $image;

        // Allowed file types
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            die("❌ Error: Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        // Move file to uploads folder
        if (!move_uploaded_file($_FILES["uploaded_image"]["tmp_name"], $target_file)) {
            die("❌ Error: Failed to upload image.");
        }
    }

    // Ensure image exists
    if (empty($image)) {
        die("❌ Error: No image selected.");
    }

    // Insert into Database
    $sql = "INSERT INTO artworks (title, description, image, price, artist_id) 
            VALUES ('$title', '$description', '$image', '$price', '$artist_id')";
    
    if ($conn->query($sql)) {
        header("Location: ../views/marketplace.php?success=1");
        exit();
    } else {
        die("❌ Database Error: " . $conn->error);
    }
}

// Fetch Artwork Listings
function getArtworks($conn) {
    $sql = "SELECT artworks.*, users.name AS artist_name FROM artworks 
            JOIN users ON artworks.artist_id = users.id";
    return $conn->query($sql);
}

// Fetch Specific Artwork (for viewing)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM artworks WHERE id = $id";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_assoc());
}
// Edit Artwork
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['edit_artwork'])) {
        $artwork_id = $data['artwork_id'];
        $title = mysqli_real_escape_string($conn, $data['title']);
        $description = mysqli_real_escape_string($conn, $data['description']);
        $price = mysqli_real_escape_string($conn, $data['price']);

        $sql = "UPDATE artworks SET title='$title', description='$description', price='$price' WHERE id='$artwork_id'";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["success" => true, "message" => "Artwork updated successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Update failed!"]);
        }
        exit();
    }

    if (isset($data['delete_artwork'])) {
        $artwork_id = $data['artwork_id'];
        $sql = "DELETE FROM artworks WHERE id='$artwork_id'";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(["success" => true, "message" => "Artwork deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Delete failed!"]);
        }
        exit();
    }
}
?>
