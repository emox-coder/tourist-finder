<?php
namespace App\Controllers;

trait ApiResponse {
    protected function sendResponse($data, $statusCode = 200) {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function sendSuccess($data = true) {
        $this->sendResponse(["success" => true, "data" => $data]);
    }

    protected function sendError($message, $statusCode = 500) {
        $this->sendResponse([
            "success" => false, 
            "error" => $message
        ], $statusCode);
    }
}
