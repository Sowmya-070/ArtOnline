<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Paint App - Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../paint-app/js/canvas.js"></script>
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background: #0056b3;
        }
        /* Manage Artwork Table */
        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .btn-edit {
            background-color: orange;
        }
        .btn-delete {
            background-color: red;
        }
    </style>
</head>
<body>
    <?php include '../views/includes/sidebar.php'; ?>
    <?php include '../views/includes/topbar.php'; ?>

    <div class="pc-container">
        <div class="pc-content">
            <h2>Add to Marketplace</h2>

            <!-- Upload Artwork to Marketplace -->
            <h3>Upload Artwork</h3>
            <form method="post" action="../controllers/marketplace.php" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <label>Select Saved Artwork or Upload:</label>
                <select name="saved_image">
                    <option value="">Select from saved artworks</option>
                    <?php
                    $result = mysqli_query($conn, "SELECT image_name FROM saveartwork WHERE user_id='" . $_SESSION['user_id'] . "'");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['image_name'] . "'>" . $row['image_name'] . "</option>";
                    }
                    ?>
                </select>
                <input type="file" name="uploaded_image" accept="image/*">
                <input type="number" name="price" placeholder="Price" required>
                <button type="submit" name="upload">Upload to Marketplace</button>
            </form>

            <!-- Manage Uploaded Artworks -->
            <h3>Manage Your Uploaded Artworks</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $artworks = mysqli_query($conn, "SELECT * FROM artworks WHERE artist_id='$user_id'");
                    while ($art = mysqli_fetch_assoc($artworks)) {
                        echo "<tr>";
                        echo "<td>" . $art['id'] . "</td>";
                        echo "<td contenteditable='true' id='title_" . $art['id'] . "'>" . $art['title'] . "</td>";
                        echo "<td contenteditable='true' id='description_" . $art['id'] . "'>" . $art['description'] . "</td>";
                        echo "<td contenteditable='true' id='price_" . $art['id'] . "'>" . $art['price'] . "</td>";
                        echo "<td><img src='../uploads/artworks/" . $art['image'] . "' width='80'></td>";
                        echo "<td>
                            <button class='btn btn-delete' onclick='deleteArtwork(" . $art['id'] . ")'>Delete</button>
                        </td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <?php include '../views/includes/footer.php'; ?>

    <script>
        

        function deleteArtwork(artwork_id) {
            if (confirm("Are you sure you want to delete this artwork?")) {
                fetch('../controllers/marketplace.php', {
                    method: 'POST',
                    body: JSON.stringify({ delete_artwork: true, artwork_id: artwork_id }),
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => alert(data.message))
                .then(() => location.reload());
            }
        }
    </script>
</body>
</html>
