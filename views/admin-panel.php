<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}
include '../config/db.php';
include '../controllers/admin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel - Online Art Studio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Responsive Table Styling */
        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            white-space: nowrap; /* Prevents text from breaking in small screens */
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            color: white;
            border-radius: 5px;
        }
        .btn-delete {
            background-color: red;
        }
        .btn-delete:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <?php include '../views/includes/sidebar.php'; ?>

    <div class="pc-container" id="content">
        <div class="pc-content">
            <h2>Admin Panel</h2>

            <!-- Manage Users -->
            <h3>Users</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    $users = getAllUsers($conn);
                    while ($user = $users->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $user['id'] . "</td>";
                        echo "<td>" . $user['name'] . "</td>";
                        echo "<td>" . $user['email'] . "</td>";
                        echo "<td>" . $user['role'] . "</td>";
                        echo "<td><button class='btn btn-delete' onclick='deleteUser(" . $user['id'] . ")'>Delete</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>

            <!-- Manage Artworks -->
            <h3>Artworks</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    $artworks = getAllArtworks($conn);
                    while ($art = $artworks->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $art['id'] . "</td>";
                        echo "<td>" . $art['title'] . "</td>";
                        echo "<td>" . $art['artist_name'] . "</td>";
                        echo "<td><button class='btn btn-delete' onclick='deleteArtwork(" . $art['id'] . ")'>Delete</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>

            
        </div>
    </div>

    <?php include '../views/includes/footer.php'; ?>

    <script>
        function deleteUser(user_id) {
    if (!confirm("Are you sure you want to delete this user?")) return;
    
    fetch('../controllers/admin.php', {
        method: 'POST',
        body: JSON.stringify({ delete_user: true, user_id: user_id }),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) location.reload();
    })
    .catch(error => console.error("Error:", error));
}


        function deleteArtwork(artwork_id) {
            fetch('../controllers/admin.php', {
                method: 'POST',
                body: JSON.stringify({ delete_artwork: true, artwork_id: artwork_id }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => alert(data.message))
            .then(() => location.reload());
        }

        function deleteReportedArtwork(artwork_id) {
            fetch('../controllers/admin.php', {
                method: 'POST',
                body: JSON.stringify({ delete_reported_artwork: true, artwork_id: artwork_id }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => alert(data.message))
            .then(() => location.reload());
        }
    </script>
</body>
</html>
