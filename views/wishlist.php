<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wishlist - Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        function removeFromWishlist(artwork_id) {
            fetch('../controllers/wishlist.php', {
                method: 'POST',
                body: JSON.stringify({ remove_wishlist: true, artwork_id: artwork_id }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            })
            .catch(error => console.error('Error:', error));
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
    <?php include '../views/includes/topbar.php'; ?>

    <div class="pc-container">
        <div class="pc-content">
            <h2>Your Wishlist</h2>
            <div class="wishlist-list">
                <?php
                $query = "SELECT wishlist.artwork_id, artworks.title, artworks.image, artworks.price 
                          FROM wishlist 
                          JOIN artworks ON wishlist.artwork_id = artworks.id 
                          WHERE wishlist.user_id = '$user_id'";
                $wishlist = mysqli_query($conn, $query);

                while ($art = $wishlist->fetch_assoc()) {
                    echo "<div class='artwork-card'>";
                    echo "<img src='../uploads/artworks/" . $art['image'] . "' alt='" . $art['title'] . "'>";
                    echo "<h4>" . $art['title'] . "</h4>";
                    echo "<p>Price: $" . $art['price'] . "</p>";
                    echo "<button onclick='removeFromWishlist(" . $art['artwork_id'] . ")'>Remove</button>";
                    echo "<button onclick='buyArtwork(" . $art['artwork_id'] . ")'>Buy Now</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <?php include '../views/includes/footer.php'; ?>
</body>
</html>
