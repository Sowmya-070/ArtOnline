<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch purchased artworks
$purchasedQuery = "SELECT DISTINCT artwork_id FROM purchases WHERE user_id = $user_id";
$purchasedResult = mysqli_query($conn, $purchasedQuery);
$purchasedArtworkIds = [];
while ($row = mysqli_fetch_assoc($purchasedResult)) {
    $purchasedArtworkIds[] = $row['artwork_id'];
}
$purchasedFilter = !empty($purchasedArtworkIds) ? implode(",", $purchasedArtworkIds) : "0"; // Avoid SQL error

// Fetch wishlisted artworks
$wishlistQuery = "SELECT DISTINCT artwork_id FROM wishlist WHERE user_id = $user_id";
$wishlistResult = mysqli_query($conn, $wishlistQuery);
$wishlistArtworkIds = [];
while ($row = mysqli_fetch_assoc($wishlistResult)) {
    $wishlistArtworkIds[] = $row['artwork_id'];
}
$wishlistFilter = !empty($wishlistArtworkIds) ? implode(",", $wishlistArtworkIds) : "0";

// Fetch top-rated artworks (excluding purchased)
$ratingQuery = "SELECT artwork_id FROM ratings WHERE rating >= 4 GROUP BY artwork_id ORDER BY COUNT(*) DESC LIMIT 10";
$ratingResult = mysqli_query($conn, $ratingQuery);
$topRatedArtworkIds = [];
while ($row = mysqli_fetch_assoc($ratingResult)) {
    $topRatedArtworkIds[] = $row['artwork_id'];
}
$topRatedFilter = !empty($topRatedArtworkIds) ? implode(",", $topRatedArtworkIds) : "0";

//print_r($topRatedFilter);

// Fetch recommended artworks (excluding purchased)
$query = "SELECT a.* FROM artworks a
          WHERE a.id NOT IN ($purchasedFilter) 
          AND a.id  IN ($wishlistFilter)
          OR a.id  IN ($topRatedFilter)
          ORDER BY RAND() LIMIT 10";

$result = mysqli_query($conn, $query);
// $recommendedArtworks = [];
// while ($art = mysqli_fetch_assoc($result)) {
//     $imagePath = '../uploads/artworks/' . $art['image'];
//     $recommendedArtworks[] = [
//         'id' => $art['id'],
//         'title' => $art['title'],
//         'image' => $imagePath,
//         'price' => $art['price']
//     ];
// }

// echo json_encode($recommendedArtworks);


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
    <h2>Recommended for You</h2>
<h4>Our recommendations are based on:</h4>
<ul>
    <strong>Purchase History:</strong> Artworks similar to those you have bought.<br>
    <strong>Wishlist:</strong> Artworks related to those you have added to your wishlist.<br>
    <strong>Highly Rated Artworks:</strong> Top-rated pieces favored by other users.<br>
</ul>

    <div class="artwork-list">
        <?php while ($art = mysqli_fetch_assoc($result)) { ?>
            <div class='artwork-card'>
                <img class='thumbnail' src="../uploads/artworks/<?php echo $art['image']; ?>" alt="<?php echo $art['title'];  ?> ">
                <h4><?php echo $art['title']; ?></h4>
                <p>Price: $<?php echo $art['price']; ?></p>
                <button onclick="buyArtwork(<?php echo $art['id']; ?>)">Buy Now</button>
            </div>
        <?php } ?>
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
