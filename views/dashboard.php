<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../config/db.php';

// Get user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../views/includes/sidebar.php'; ?>
    <?php include '../views/includes/topbar.php'; ?>

    <div class="pc-container">
        <div class="pc-content">
            <h2>Welcome, <?php echo $user['name']; ?>!</h2>
            
            <?php if ($user['role'] == 'artist') { ?>
                <h3>Artist Dashboard</h3>
                <p>Upload, create, and manage your artwork.</p>
                <a href="create-art.php">Create Art</a>
                <a href="marketplace.php">View Marketplace</a>
            <?php } elseif ($user['role'] == 'buyer') { ?>
                <h3>Buyer Dashboard</h3>
                <p>Explore, buy, and review artworks.</p>
                <a href="marketplace.php">Browse Marketplace</a>
                <a href="wishlist.php">View Wishlist</a>
            <?php } elseif ($user['role'] == 'admin') { ?>
                <h3>Admin Dashboard</h3>
                <p>Manage users, artworks, and platform settings.</p>
                <a href="admin-panel.php">Go to Admin Panel</a>
            <?php } ?>

        </div>
    </div>

    <?php include '../views/includes/footer.php'; ?>
</body>
</html>
