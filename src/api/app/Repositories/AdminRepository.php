<?php
namespace App\Repositories;

interface AdminRepository {
    public function getAllAdmins();
    public function getAdmin($id);
    public function addAdmin($data);
    public function updateAdmin($id, $data);
    public function deleteAdmin($id);
    public function usernameExists($username);
    public function emailExists($email);
    public function getAdminCount();
}
