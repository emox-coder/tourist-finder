<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\AdminController;
use App\Controllers\DashboardController;

// Enable error reporting but prevent HTML output
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Basic routing setup
$uri = $_GET['uri'] ?? $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Helper for regex routing
function matchRoute($pattern, $uri) {
    if (preg_match($pattern, $uri, $matches)) {
        return $matches;
    }
    return false;
}

try {
    $admin = new AdminController();
    $dashboard = new DashboardController();

    // PUBLIC DATA
    if ($uri === "/api/top-destinations" && $method === "GET") $admin->getTopDestinations();
    if ($uri === "/api/three-cards" && $method === "GET") $admin->getAllThreeCards();

    // ADMIN: THREE CARDS
    if ($uri === "/api/admin/three-cards" && $method === "GET") $admin->getAllThreeCards();
    if ($uri === "/api/admin/three-cards" && $method === "POST") $admin->addThreeCard();
    if ($matches = matchRoute('#^/api/admin/three-cards/(\d+)$#', $uri)) {
        $id = $matches[1];
        if ($method === "GET") $admin->getThreeCard($id);
        if ($method === "PUT") $admin->updateThreeCard($id);
        if ($method === "DELETE") $admin->deleteThreeCard($id);
    }

    // ADMIN: ATTRACTIONS
    if ($uri === "/api/admin/attractions" && $method === "GET") $admin->listAttractions();
    if ($uri === "/api/admin/attractions" && $method === "POST") $admin->addAttraction();
    if ($matches = matchRoute('#^/api/admin/attractions/(\d+)$#', $uri)) {
        $id = $matches[1];
        if ($method === "GET") $admin->getAttractionById($id);
        if ($method === "PUT" || ($method === "POST" && ($_POST['_method'] ?? '') === 'PUT')) $admin->updateAttraction($id);
        if ($method === "DELETE") $admin->deleteAttraction($id);
    }

    // ADMIN: ACCOUNTS
    if ($uri === "/api/admin/admins" && $method === "GET") $admin->getAllAdmins();
    if ($uri === "/api/admin/admins" && $method === "POST") $admin->addAdmin();
    if ($matches = matchRoute('#^/api/admin/admins/(\d+)$#', $uri)) {
        $id = $matches[1];
        if ($method === "GET") $admin->getAdmin($id);
        if ($method === "PUT") $admin->updateAdmin($id);
        if ($method === "DELETE") $admin->deleteAdmin($id);
    }

    // DASHBOARD
    if ($uri === "/dashboard") $dashboard->index();

    // DEFAULT: 404
    http_response_code(404);
    echo json_encode(["error" => "Endpoint not found", "uri" => $uri]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
