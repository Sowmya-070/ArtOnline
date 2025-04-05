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

            <!-- Search and Filter -->
            <div class="search-filter">
                <input type="text" id="searchInput" placeholder="Search artworks..." onkeyup="filterArtworks()">
                <select id="filterOption" onchange="filterArtworks()">
                    <option value="all">All</option>
                    <option value="low">Price: Low to High</option>
                    <option value="high">Price: High to Low</option>
                    <option value="rating">Highest Rating</option>
                </select>
            </div>

            <!-- Display Artwork -->
            <h3>Available Artworks</h3>
            <div class="artwork-list" id="artworkList">
                <?php
                $artworks = getArtworks($conn);
                $artworkData = [];
                while ($art = $artworks->fetch_assoc()) {
                    $imagePath = '../uploads/artworks/' . $art['image'];

                    // Fetch the average rating
                    $ratingQuery = "SELECT AVG(rating) as avg_rating FROM ratings WHERE artwork_id = " . $art['id'];
                    $ratingResult = mysqli_query($conn, $ratingQuery);
                    $ratingRow = mysqli_fetch_assoc($ratingResult);
                    $avgRating = round($ratingRow['avg_rating'], 1);
                    $baseURL = "https://111.111.111.11/uploads/artworks/";
                    $imagePath1 = $baseURL . $art['image'];
                    
                    $artworkData[] = [
                        'id' => $art['id'],
                        'title' => $art['title'],
                        'description' => $art['description'],
                        'artist' => $art['artist_name'],
                        'price' => $art['price'],
                        'rating' => $avgRating,
                        'image' => $imagePath,
                        'shareUrl' => $imagePath1
                    ];
                   
                }
                ?>
            </div>
        </div>
        <div id="imagePreviewModal" class="modal">
        <span class="close" onclick="closePreview()">&times;</span>
        <div class="modal-content">
            <img id="previewImage" style="width: 100%; height: auto;">
        </div>
    </div>
    </div>

    <script>
        let userRole = "<?php echo $_SESSION['user_role']; ?>";
        let artworks = <?php echo json_encode($artworkData); ?>;
                
        function renderArtworks(data, userRole) {
    const container = document.getElementById('artworkList');
    container.innerHTML = '';

    data.forEach(art => {
        let card = `<div class='artwork-card'>
            <img class='thumbnail' src="${art.image}" alt="${art.title}" onclick='previewImage("${art.image}")'>
            <h4>${art.title}</h4>
            <p>${art.description}</p>
            <p>By: ${art.artist}</p>
            <p>Price: $${art.price}</p>
            <p>Average Rating: ${art.rating ? art.rating : "No Ratings Yet"}</p>`;

        // Rating System (Only buyers can rate)
        if (userRole === 'buyer') {
            card += `<div class='rating'>`;
            for (let i = 5; i >= 1; i--) {
                card += `<input type='radio' id='star${i}_${art.id}' name='rating_${art.id}' value='${i}'>
                         <label for='star${i}_${art.id}'>★</label>`;
            }
            card += `</div>
                     <button onclick='submitRating(${art.id})'>Rate</button>`;
        }

        // Wishlist & Buy Buttons (Only buyers can see)
        if (userRole === 'buyer') {
            card += `<button onclick='addToWishlist(${art.id})'>Add to Wishlist</button>
                     <button onclick='buyArtwork(${art.id})'>Buy Now</button>`;
        }

        // WhatsApp Share Button
        card += `<button onclick='shareOnWhatsApp("${art.shareUrl}")'>Share on WhatsApp</button>
                </div>`;

        container.innerHTML += card;
    });
}

renderArtworks(artworks, userRole);

        function filterArtworks() {
            let searchQuery = document.getElementById("searchInput").value.toLowerCase();
            let filterValue = document.getElementById("filterOption").value;
            let filteredArtworks = artworks.filter(art => art.title.toLowerCase().includes(searchQuery));

            if (filterValue === "low") {
                filteredArtworks.sort((a, b) => a.price - b.price);
            } else if (filterValue === "high") {
                filteredArtworks.sort((a, b) => b.price - a.price);
            } else if (filterValue === "rating") {
                filteredArtworks.sort((a, b) => b.rating - a.rating);
            }
            renderArtworks(filteredArtworks);
        }

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
