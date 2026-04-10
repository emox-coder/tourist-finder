<?php
require_once __DIR__ . '/../../src/vendor/autoload.php';

use App\Controllers\AdminController;
use App\Controllers\DashboardController;

// Enable error reporting but prevent HTML output
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Set JSON header for all responses
header("Content-Type: application/json");

try {

    // Get URI from query parameter or fallback to REQUEST_URI
    $uri = $_GET['uri'] ?? $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    // Public API endpoints for landing page
    if ($uri === "/api/top-destinations" && $method === "GET") {
        (new AdminController())->getTopDestinations();
        exit;
    }

    if ($uri === "/api/three-cards" && $method === "GET") {
        (new AdminController())->getThreeCards();
        exit;
    }

    // Admin API endpoints
    if (strpos($uri, "/api/admin/three-cards") === 0) {
        $parts = explode("/", $uri);
        $id = isset($parts[4]) ? (int)$parts[4] : null;
        
        if ($method === "GET" && $id) {
            (new AdminController())->getThreeCard($id);
        } elseif ($method === "GET") {
            (new AdminController())->getAllThreeCards();
        } elseif ($method === "POST") {
            (new AdminController())->addThreeCard();
        } elseif ($method === "PUT" && $id) {
            (new AdminController())->updateThreeCard($id);
        } elseif ($method === "DELETE" && $id) {
            (new AdminController())->deleteThreeCard($id);
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
        }
        exit;
    }

    // Admin API endpoints for admin account management
    if (strpos($uri, "/api/admin/admins") === 0) {
        $parts = explode("/", $uri);
        $id = isset($parts[4]) ? (int)$parts[4] : null;
        
        if ($method === "GET" && $id) {
            (new AdminController())->getAdmin($id);
        } elseif ($method === "GET") {
            (new AdminController())->getAllAdmins();
        } elseif ($method === "POST") {
            (new AdminController())->addAdmin();
        } elseif ($method === "PUT" && $id) {
            (new AdminController())->updateAdmin($id);
        } elseif ($method === "DELETE" && $id) {
            (new AdminController())->deleteAdmin($id);
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
        }
        exit;
    }

    // Admin API endpoints for attractions management
    if ($uri === "/api/admin/attractions" && $method === "GET") {
        (new AdminController())->listAttractions();
        exit;
    }

    if ($uri === "/api/admin/attractions" && $method === "POST") {
        (new AdminController())->addAttraction();
        exit;
    }

    // Handle PUT/PATCH for update (via _method override or direct)
    if (preg_match('#^/api/admin/attractions/(\d+)$#', $uri, $matches)) {
        $id = $matches[1];
        
        if ($method === "GET") {
            (new AdminController())->getAttractionById($id);
            exit;
        }
        
        if ($method === "POST" && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
            (new AdminController())->updateAttraction($id);
            exit;
        }
        
        if ($method === "PUT") {
            (new AdminController())->updateAttraction($id);
            exit;
        }
        
        if ($method === "DELETE") {
            (new AdminController())->deleteAttraction($id);
            exit;
        }
    }

    // Dashboard endpoint
    if ($uri === "/dashboard") {
        (new DashboardController())->index();
        exit;
    }

    // Handle 404
    http_response_code(404);
    echo json_encode(["error" => "Endpoint not found", "uri" => $uri, "method" => $method]);

} catch (Exception $e) {
    // Catch any PHP errors and return as JSON
    error_log("API Exception: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    http_response_code(500);
    echo json_encode([
        "error" => "Server error", 
        "message" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine(),
        "debug" => "Check server logs for details"
    ]);
} catch (Error $e) {
    // Catch fatal errors
    error_log("API Fatal Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    http_response_code(500);
    echo json_encode([
        "error" => "Fatal server error", 
        "message" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine(),
        "debug" => "Check server logs for details"
    ]);
}
?>
