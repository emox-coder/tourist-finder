<?php
namespace App\Controllers;

session_start();
require_once __DIR__ . "/../../config/config.php";

$config = require __DIR__ . "/../../config/config.php";

function getDbConnection() {
    global $config;
    try {
        $pdo = new \PDO(
            "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']}",
            $config['db']['user'],
            $config['db']['pass']
        );
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }
}

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required"]);
        exit;
    }
    
    $pdo = getDbConnection();
    if (!$pdo) {
        echo json_encode(["success" => false, "message" => "Database connection failed"]);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    // Temporary diagnostic: log what we found
    error_log("Login attempt - Email: $email, Found: " . ($admin ? "YES" : "NO"));
    if ($admin) {
        error_log("Admin record: " . json_encode(['id' => $admin['id'], 'email' => $admin['email']]));
        error_log("Password verify result: " . (password_verify($password, $admin['password']) ? "PASS" : "FAIL"));
        error_log("Stored hash: " . substr($admin['password'], 0, 30) . "...");
    }
    
    // TEMPORARY: Accept plain text passwords for testing
    if ($admin && ($password === $admin['password'] || password_verify($password, $admin['password']))) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_id'] = $admin['id'];
        
        echo json_encode(["success" => true, "message" => "Login successful"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid email or password"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
