<?php
error_reporting(0);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';
include '../controllers/marketplace.php';

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Marketplace - Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        function previewImage(src) {
            let modal = document.getElementById('imagePreviewModal');
            let modalImg = document.getElementById('previewImage');
            modal.style.display = "flex";
            modalImg.src = src;
        }
        function closePreview() {
            document.getElementById('imagePreviewModal').style.display = "none";
        }

        function addToWishlist(artwork_id) {
    fetch('../controllers/wishlist.php', {
        method: 'POST',
        body: JSON.stringify({ add_wishlist: true, artwork_id: artwork_id }),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            location.reload(); // Refresh page after adding to wishlist
        }
    })
    .catch(error => console.error('Error:', error));
}


        function submitRating(artwork_id) {
            let rating = document.querySelector(`input[name="rating_${artwork_id}"]:checked`);
            if (!rating) {
                alert("Please select a rating.");
                return;
            }
            fetch('../controllers/rating.php', {
                method: 'POST',
                body: JSON.stringify({ rate_artwork: true, artwork_id: artwork_id, rating: rating.value }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
        }

        function buyArtwork(artwork_id) {
            fetch('../controllers/purchase.php', {
                method: 'POST',
                body: JSON.stringify({ buy: true, artwork_id: artwork_id }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
        }
    </script>
    <style>
        .thumbnail {
            width: 100px;
            height: auto;
            cursor: pointer;
            border-radius: 5px;
            transition: transform 0.2s;
        }
        .thumbnail:hover {
            transform: scale(1.1);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            max-width: 50%;
            max-height: 50%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            background-color: white;
            padding: 10px;
        }
        .artwork-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .artwork-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .artwork-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .rating {
            display: flex;
            justify-content: center;
            flex-direction: row-reverse;
            gap: 5px;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 24px;
            cursor: pointer;
            color: gray;
        }
        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: gold;
        }
    </style>
</head>
<body>
    <?php include '../views/includes/sidebar.php'; ?>
    <?php include '../views/includes/topbar.php'; ?>

    <div class="pc-container">
        <div class="pc-content">
            <h2>Marketplace</h2>

            <!-- Display Artwork -->
            <h3>Available Artworks</h3>
            <div class="artwork-list">
                <?php
                $artworks = getArtworks($conn);
                //print_r($artworks);
                while ($art = $artworks->fetch_assoc()) {
                    //print_r($art);
                    $imagePath = '../uploads/artworks/' . $art['image'];

                    // Fetch the average rating
                    $ratingQuery = "SELECT AVG(rating) as avg_rating FROM ratings WHERE artwork_id = " . $art['id'];
                    $ratingResult = mysqli_query($conn, $ratingQuery);
                    $ratingRow = mysqli_fetch_assoc($ratingResult);
                    $avgRating = round($ratingRow['avg_rating'], 1);

                    echo "<div class='artwork-card'>";
                    echo "<img class='thumbnail' src='" . htmlspecialchars($imagePath) . "' alt='" . htmlspecialchars($art['title']) . "' onclick='previewImage(\"" . htmlspecialchars($imagePath) . "\")'>";
                    echo "<h4>" . htmlspecialchars($art['title']) . "</h4>";
                    echo "<p>" . htmlspecialchars($art['description']) . "</p>";
                    echo "<p>By: " . htmlspecialchars($art['artist_name']) . "</p>";
                    echo "<p>Price: $" . htmlspecialchars($art['price']) . "</p>";

                    // Display Rating
                    echo "<p>Average Rating: " . ($avgRating ? $avgRating : "No Ratings Yet") . "</p>";

                    // Rating System (Only buyers can rate)
                    if ($user_role == 'buyer') {
                        echo "<div class='rating'>";
                        for ($i = 5; $i >= 1; $i--) {
                            echo "<input type='radio' id='star{$i}_{$art['id']}' name='rating_{$art['id']}' value='{$i}'>";
                            echo "<label for='star{$i}_{$art['id']}'>★</label>";
                        }
                        echo "</div>";
                        echo "<button onclick='submitRating(" . $art['id'] . ")'>Rate</button>";
                    }

                    // Wishlist & Buy Buttons
                    if ($user_role == 'buyer') {
                        echo "<button onclick='addToWishlist(" . $art['id'] . ")'>Add to Wishlist</button>";
                        echo "<button onclick='buyArtwork(" . $art['id'] . ")'>Buy Now</button>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <div id="imagePreviewModal" class="modal">
        <span class="close" onclick="closePreview()">&times;</span>
        <div class="modal-content">
            <img id="previewImage" style="width: 100%; height: auto;">
        </div>
    </div>

    <?php include '../views/includes/footer.php'; ?>
</body>
</html>
