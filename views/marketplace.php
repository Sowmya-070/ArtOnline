<?php
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
    <title>Dashboard - Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
    
</head>
<body>
    <?php include '../views/includes/sidebar.php'; ?>

    <div class="content" id="content">
        <div class="pc-content">
            <h2>Marketplace</h2>

            <!-- Display Artwork -->
            <h3>Available Artworks</h3>
            <div class="artwork-list">
                <?php
                $artworks = getArtworks($conn);
                while ($art = $artworks->fetch_assoc()) {
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
                    $baseURL = "https://111.111.111.11/uploads/artworks/"; // Change this to your actual domain
                    $imagePath1 = $baseURL . $art['image'];

                    echo "<button onclick='shareOnWhatsApp(\"" . $imagePath1 . "\")'>Share on WhatsApp</button>";

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
    <script>
function shareOnWhatsApp(imageUrl) {
    let message = "Check out this amazing artwork! " + imageUrl;
    let whatsappUrl = "https://web.whatsapp.com/send?text=" + encodeURIComponent(message);

    // Check if the user is on a mobile device
    if (/Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        whatsappUrl = "https://api.whatsapp.com/send?text=" + encodeURIComponent(message);
    }

    window.open(whatsappUrl, "_blank");
}

</script>
    <?php include '../views/includes/footer.php'; ?>
</body>
</html>
