<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
require_once __DIR__ . "/../../Backend/config/config.php";
$config = require __DIR__ . "/../../Backend/config/config.php";

function getDbConnection() {
    global $config;
    try {
        $pdo = new PDO(
            "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']}",
            $config['db']['user'],
            $config['db']['pass']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.html");
        exit;
    }
}

function login($email, $password) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_id'] = $admin['id'];
        return true;
    }
    
    return false;
}

function logout() {
    session_destroy();
    header("Location: login.html");
    exit;
}

function getAdminEmail() {
    return $_SESSION['admin_email'] ?? '';
}
?>
