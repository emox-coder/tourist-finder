<?php
namespace App\Controllers;

use App\UseCases\ManageAttraction;
use App\UseCases\ManageAdmin;

class AdminController {
    public function addAttraction() {
        header("Content-Type: application/json");
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = (new ManageAttraction())->create($data);
            echo json_encode(["success" => $result]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function listAttractions() {
        header("Content-Type: application/json");
        try {
            echo json_encode((new ManageAttraction())->getAll());
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function getTopDestinations() {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET");
        try {
            echo json_encode((new ManageAttraction())->getTopDestinations());
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function getAttractionById($id) {
        header("Content-Type: application/json");
        try {
            $result = (new ManageAttraction())->getById($id);
            echo json_encode($result ? $result : ["error" => "Attraction not found"]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function updateAttraction($id) {
        header("Content-Type: application/json");
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = (new ManageAttraction())->update($id, $data);
            echo json_encode(["success" => $result]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function getAllAdmins() {
        header("Content-Type: application/json");
        try {
            $result = (new ManageAdmin())->getAllAdmins();
            
            // Clean output buffer to prevent BOM issues
            if (ob_get_length()) {
                ob_clean();
            }
            
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function getAdmin($id) {
        header("Content-Type: application/json");
        try {
            $result = (new ManageAdmin())->getAdmin($id);
            
            // Clean output buffer to prevent BOM issues
            if (ob_get_length()) {
                ob_clean();
            }
            
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function addAdmin() {
        header("Content-Type: application/json");
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = (new ManageAdmin())->addAdmin($data);
            
            // Clean output buffer to prevent BOM issues
            if (ob_get_length()) {
                ob_clean();
            }
            
            echo json_encode(["success" => $result]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function updateAdmin($id) {
        header("Content-Type: application/json");
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = (new ManageAdmin())->updateAdmin($id, $data);
            
            // Clean output buffer to prevent BOM issues
            if (ob_get_length()) {
                ob_clean();
            }
            
            echo json_encode(["success" => $result]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function deleteAdmin($id) {
        header("Content-Type: application/json");
        try {
            $result = (new ManageAdmin())->deleteAdmin($id);
            
            // Clean output buffer to prevent BOM issues
            if (ob_get_length()) {
                ob_clean();
            }
            
            echo json_encode(["success" => $result]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function getThreeCards() {
        header("Content-Type: application/json");
        try {
            $result = (new ManageAttraction())->getThreeCards();
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function getAllThreeCards() {
        header("Content-Type: application/json");
        try {
            $result = (new ManageAttraction())->getAllThreeCards();
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function getThreeCard($id) {
        header("Content-Type: application/json");
        try {
            $result = (new ManageAttraction())->getThreeCard($id);
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function addThreeCard() {
        header("Content-Type: application/json");
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = (new ManageAttraction())->addThreeCard($data);
            echo json_encode(["success" => $result]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function updateThreeCard($id) {
        header("Content-Type: application/json");
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = (new ManageAttraction())->updateThreeCard($id, $data);
            echo json_encode(["success" => $result]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function deleteThreeCard($id) {
        header("Content-Type: application/json");
        try {
            $result = (new ManageAttraction())->deleteThreeCard($id);
            echo json_encode(["success" => $result]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function deleteAttraction($id) {
        header("Content-Type: application/json");
        try {
            $result = (new ManageAttraction())->delete($id);
            echo json_encode(["success" => $result]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }
}
?>
