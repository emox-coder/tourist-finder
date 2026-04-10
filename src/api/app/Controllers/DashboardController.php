<?php
namespace App\Controllers;

use App\UseCases\ViewDashboard;

class DashboardController {
    public function index() {
        echo json_encode((new ViewDashboard())->stats());
    }
}
