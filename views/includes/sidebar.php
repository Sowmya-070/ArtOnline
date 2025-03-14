<!-- Sidebar Menu -->
<nav class="pc-sidebar" id="sidebar">
    <span class="closebtn" onclick="closeNav()">&times;</span>
    <ul class="pc-navbar">
        <li class="pc-item"><a href="dashboard.php" class="pc-link">Dashboard</a></li>
        <li class="pc-item"><a href="marketplace.php" class="pc-link">Marketplace</a></li>

        <?php if ($_SESSION['user_role'] == 'artist') { ?>
            <li class="pc-item"><a href="add-to-market.php" class="pc-link">Add to Market</a></li>
            <li class="pc-item"><a href="create-art.php" class="pc-link">Create Art</a></li>
        <?php } elseif ($_SESSION['user_role'] == 'buyer') { ?>
            <li class="pc-item"><a href="wishlist.php" class="pc-link">Wishlist</a></li>
        <?php } elseif ($_SESSION['user_role'] == 'admin') { ?>
            <li class="pc-item"><a href="admin-panel.php" class="pc-link">Admin Panel</a></li>
        <?php } ?>

        <li class="pc-item"><a href="../logout.php" class="pc-link">Logout</a></li>
    </ul>
</nav>

<!-- Button to Open Sidebar -->
<button class="toggle-btn" id="sidebarToggle">&#9776;</button>


<script>
    function openNav() {
        document.getElementById("sidebar").style.width = "250px"; // Open sidebar
        document.getElementById("content").style.marginLeft = "250px"; // Shift content
        
    }

    function closeNav() {
        document.getElementById("sidebar").style.width = "0"; // Close sidebar
        document.getElementById("content").style.marginLeft = "0"; // Reset content position
       
    }

    document.addEventListener('DOMContentLoaded', function () {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        sidebarToggle.addEventListener('click', function () {
            if (sidebar.style.width === "250px") {
                closeNav();
            } else {
                openNav();
            }
        });

        // Close sidebar when clicking on a menu item
        document.querySelectorAll('.pc-item .pc-link').forEach(item => {
            item.addEventListener('click', closeNav);
        });
    });
</script>