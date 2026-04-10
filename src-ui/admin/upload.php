<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/auth.php';

// Require login
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['image'])) {
    echo json_encode(["success" => false, "message" => "No file uploaded"]);
    exit;
}

$image = $_FILES['image'];

// Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($image['type'], $allowedTypes)) {
    echo json_encode(["success" => false, "message" => "Invalid file type. Only JPG, PNG, WebP, and GIF are allowed"]);
    exit;
}

// Validate file size (5MB max)
if ($image['size'] > 5 * 1024 * 1024) {
    echo json_encode(["success" => false, "message" => "File size must be less than 5MB"]);
    exit;
}

// Create upload directory if it doesn't exist
$uploadDir = __DIR__ . '/../assets/img/destinations/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate unique filename
$extension = pathinfo($image['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '_' . time() . '.' . $extension;
$filepath = $uploadDir . $filename;

// Move uploaded file
if (move_uploaded_file($image['tmp_name'], $filepath)) {
    // Return the path relative to the web root
    $relativePath = 'assets/img/destinations/' . $filename;
    echo json_encode([
        "success" => true,
        "path" => $relativePath,
        "filename" => $filename
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to move uploaded file"]);
}
?>
