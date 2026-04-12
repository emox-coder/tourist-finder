<?php
namespace App\Controllers;

use App\UseCases\ManageAttraction;
use App\UseCases\ManageAdmin;
use Infrastructure\Repositories\AttractionRepositoryImpl;
use Infrastructure\Repositories\AdminRepositoryImpl;
use Exception;

class AdminController {
    use ApiResponse;

    private $manageAttraction;
    private $manageAdmin;

    public function __construct() {
        $attractionRepo = new AttractionRepositoryImpl();
        $adminRepo = new AdminRepositoryImpl();
        
        $this->manageAttraction = new ManageAttraction($attractionRepo);
        $this->manageAdmin = new ManageAdmin($adminRepo);
    }

    public function addAttraction() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->manageAttraction->create($data);
            $this->sendSuccess($result);
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    public function listAttractions() {
        try {
            $this->sendResponse($this->manageAttraction->getAll());
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    public function getTopDestinations() {
        try {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: GET");
            $this->sendResponse($this->manageAttraction->getTopDestinations());
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    public function getAttractionById($id) {
        try {
            $result = $this->manageAttraction->getById($id);
            if (!$result) {
                $this->sendError("Attraction not found", 404);
            }
            $this->sendResponse($result);
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    public function updateAttraction($id) {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->manageAttraction->update($id, $data);
            $this->sendSuccess($result);
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    public function deleteAttraction($id) {
        try {
            $result = $this->manageAttraction->delete($id);
            $this->sendSuccess($result);
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    public function getAllAdmins() {
        try {
            $this->sendResponse($this->manageAdmin->getAllAdmins());
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    public function getAdmin($id) {
        try {
            $result = $this->manageAdmin->getAdmin($id);
            if (!$result) {
                $this->sendError("Admin not found", 404);
            }
            $this->sendResponse($result);
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    public function addAdmin() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->manageAdmin->addAdmin($data);
            $this->sendSuccess($result);
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    public function updateAdmin($id) {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->manageAdmin->updateAdmin($id, $data);
            $this->sendSuccess($result);
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    public function deleteAdmin($id) {
        try {
            $result = $this->manageAdmin->deleteAdmin($id);
            $this->sendSuccess($result);
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }
}
