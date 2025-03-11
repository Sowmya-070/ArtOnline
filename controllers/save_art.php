<?php
session_start();
include '../config/db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["image"]) && isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"]; // Get logged-in user ID
        $imageData = $_POST["image"];

        // Decode Base64 Image
        $imageData = str_replace("data:image/png;base64,", "", $imageData);
        $imageData = str_replace(" ", "+", $imageData);
        $decodedImage = base64_decode($imageData);

        // Ensure Upload Directory Exists
        $uploadDir = "../uploads/artworks/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if not exists
        }

        // Generate Unique Filename
        $fileName = "artwork_" . time() . ".png";
        $filePath = $uploadDir . $fileName;

        // Save Image to Server
        if (file_put_contents($filePath, $decodedImage)) {
            // Save record to the database
            $stmt = $conn->prepare("INSERT INTO saveartwork (user_id, image_name, image_path, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iss", $user_id, $fileName, $filePath);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Image saved!", "path" => $filePath]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to save record in database."]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Failed to save image."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No image data received or user not logged in."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
