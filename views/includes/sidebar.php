<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="dashboard.php" class="b-brand text-primary">
                <img src="../assets/images/logo-white-sm.svg" alt="logo image" class="logo-lg">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item"><a href="dashboard.php" class="pc-link"><span>Dashboard</span></a></li>
                <li class="pc-item"><a href="marketplace.php" class="pc-link"><span>Marketplace</span></a></li>
                
                <?php if ($_SESSION['user_role'] == 'artist') { ?>
                    <li class="pc-item"><a href="add-to-market.php" class="pc-link"> <span>Add to market</span></a></li>
                    <li class="pc-item"><a href="create-art.php" class="pc-link"> <span>Create art</span></a></li>
                <?php } elseif ($_SESSION['user_role'] == 'buyer') { ?>
                    <li class="pc-item"><a href="wishlist.php" class="pc-link"><span>Wishlist</span></a></li>
                <?php } elseif ($_SESSION['user_role'] == 'admin') { ?>
                    <li class="pc-item"><a href="admin-panel.php" class="pc-link"> <span>Admin Panel</span></a></li>
                <?php } ?>

                <li class="pc-item"><a href="../logout.php" class="pc-link"><span>Logout</span></a></li>
            </ul>
        </div>
    </div>
</nav>
