<?php
namespace App\UseCases;

use Infrastructure\Repositories\AdminManagementRepositoryImpl;

class ManageAdminManagement {
    public function getAll() {
        return (new AdminManagementRepositoryImpl())->getAll();
    }
}
