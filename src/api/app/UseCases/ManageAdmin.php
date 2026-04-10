<?php
namespace App\UseCases;

use Infrastructure\Repositories\AdminRepositoryImpl;

class ManageAdmin {
    private $repo;

    public function __construct() {
        $this->repo = new AdminRepositoryImpl();
    }

    public function getAllAdmins() {
        return $this->repo->getAllAdmins();
    }

    public function getAdmin($id) {
        return $this->repo->getAdmin($id);
    }

    public function addAdmin($data) {
        // Validate required fields
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            throw new Exception("Username, email, and password are required");
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check if username already exists
        if ($this->repo->usernameExists($data['username'])) {
            throw new Exception("Username already exists");
        }

        // Check if email already exists
        if ($this->repo->emailExists($data['email'])) {
            throw new Exception("Email already exists");
        }

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        return $this->repo->addAdmin([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role' => $data['role'] ?? 'admin'
        ]);
    }

    public function updateAdmin($id, $data) {
        // Check if admin exists
        $admin = $this->repo->getAdmin($id);
        if (!$admin) {
            throw new Exception("Admin not found");
        }

        // Validate email format if provided
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check if username already exists (excluding current admin)
        if (isset($data['username']) && $data['username'] !== $admin['username']) {
            if ($this->repo->usernameExists($data['username'])) {
                throw new Exception("Username already exists");
            }
        }

        // Check if email already exists (excluding current admin)
        if (isset($data['email']) && $data['email'] !== $admin['email']) {
            if ($this->repo->emailExists($data['email'])) {
                throw new Exception("Email already exists");
            }
        }

        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']); // Don't update password if not provided
        }

        return $this->repo->updateAdmin($id, $data);
    }

    public function deleteAdmin($id) {
        // Check if admin exists
        $admin = $this->repo->getAdmin($id);
        if (!$admin) {
            throw new Exception("Admin not found");
        }

        // Prevent deletion of the last admin
        $adminCount = $this->repo->getAdminCount();
        if ($adminCount <= 1) {
            throw new Exception("Cannot delete the last admin account");
        }

        return $this->repo->deleteAdmin($id);
    }
}
?>
