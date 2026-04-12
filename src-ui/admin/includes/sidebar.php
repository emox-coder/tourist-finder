<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="../assets/img/logo.png" alt="TAF">
        <h2>TAF Admin</h2>
    </div>
    <nav class="sidebar-nav">
        <a href="top-destinations.php" class="<?php echo $current_page == 'top-destinations.php' ? 'active' : ''; ?>">
            <i class="fas fa-star"></i>
            Top Destinations
        </a>
        <a href="three-cards.php" class="<?php echo $current_page == 'three-cards.php' ? 'active' : ''; ?>">
            <i class="fas fa-th-large"></i>
            Three Cards
        </a>
        <a href="attractions.php" class="<?php echo $current_page == 'attractions.php' ? 'active' : ''; ?>">
            <i class="fas fa-map-marker-alt"></i>
            All Attractions
        </a>
        <a href="admin-accounts.php" class="<?php echo $current_page == 'admin-accounts.php' ? 'active' : ''; ?>">
            <i class="fas fa-users-cog"></i>
            Admin Accounts
        </a>
        <a href="../pages/landing-page.php" target="_blank">
            <i class="fas fa-external-link-alt"></i>
            View Website
        </a>
    </nav>
</aside>
