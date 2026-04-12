<?php
namespace Infrastructure\Repositories;

use Infrastructure\Database\Database;

use App\Repositories\AdminRepository;

class AdminRepositoryImpl implements AdminRepository {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAllAdmins() {
        $stmt = $this->conn->prepare("SELECT id, email, created_at FROM admins ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAdmin($id) {
        $stmt = $this->conn->prepare("SELECT id, email, created_at FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addAdmin($data) {
        $stmt = $this->conn->prepare("INSERT INTO admins (email, password, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([
            $data['email'],
            $data['password']
        ]);
    }

    public function updateAdmin($id, $data) {
        $fields = [];
        $values = [];
        
        if (isset($data['email'])) {
            $fields[] = "email = ?";
            $values[] = $data['email'];
        }
        
        if (isset($data['password'])) {
            $fields[] = "password = ?";
            $values[] = $data['password'];
        }
        
        if (empty($fields)) {
            return false; // No fields to update
        }
        
        $values[] = $id;
        $sql = "UPDATE admins SET " . implode(", ", $fields) . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($values);
    }

    public function deleteAdmin($id) {
        // Get admin details before deletion for logging/cleanup
        $stmt = $this->conn->prepare("SELECT email FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        $admin = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Delete the record
        $stmt = $this->conn->prepare("DELETE FROM admins WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        // Log the deletion for audit purposes
        if ($result && $admin) {
            $this->logAdminDeletion($admin);
        }
        
        return $result;
    }

    private function logAdminDeletion($admin) {
        // Create a simple log entry for admin deletion
        $logFile = __DIR__ . '/../../../logs/admin_deletions.log';
        $logDir = dirname($logFile);
        
        // Create logs directory if it doesn't exist
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logEntry = sprintf(
            "[%s] Admin deleted: %s\n",
            date('Y-m-d H:i:s'),
            $admin['email']
        );
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    public function getAdminCount() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM admins");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function usernameExists($username) {
        // Username field doesn't exist in current schema, return false
        return false;
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) !== false;
    }
}
