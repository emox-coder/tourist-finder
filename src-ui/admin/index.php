<?php
// Redirect to login if not logged in, otherwise to top-destinations
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: top-destinations.php");
} else {
    header("Location: login.php");
}
exit;