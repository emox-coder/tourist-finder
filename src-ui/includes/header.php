<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!--header-->
<div data-aos="fade-down" data-aos-duration="1500" data-aos-delay="300" class="container">
    <div class="logo">
        <img src="../assets/img/logo.png">
    </div>
    <div class="search-home-container">
        <ul>
            <li><a href="landing-page.php" class="nav-link <?php echo $current_page == 'landing-page.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="packages.php" class="nav-link <?php echo $current_page == 'packages.php' ? 'active' : ''; ?>">Packages</a></li>
            <li><a href="community.php" class="nav-link <?php echo $current_page == 'community.php' ? 'active' : ''; ?>">Community</a></li>
            <li><a href="about.php" class="nav-link <?php echo $current_page == 'about.php' ? 'active' : ''; ?>">About</a></li>
        </ul>
    </div>
    <div class="signin-button">
        <a href="dashboard.html" id="loginModalBtn">Get Started</a>
    </div>
</div>
<!--header-->
