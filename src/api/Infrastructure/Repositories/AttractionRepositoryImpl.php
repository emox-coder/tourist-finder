<?php
namespace Infrastructure\Repositories;

use Infrastructure\Database\Database;

class AttractionRepositoryImpl {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function create($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO attractions (name, location, description, category, image_url, is_top_destination, display_order)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['name'],
            $data['location'],
            $data['description'] ?? '',
            $data['category'] ?? 'city',
            $data['image_url'] ?? null,
            $data['is_top_destination'] ?? 0,
            $data['display_order'] ?? 0
        ]);
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM attractions ORDER BY display_order ASC, id DESC")
                          ->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTopDestinations() {
        $stmt = $this->conn->prepare(
            "SELECT id, name, location, description, image_url, category, is_top_destination, display_order 
             FROM attractions 
             WHERE is_top_destination = 1 
             ORDER BY display_order ASC, id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM attractions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        // Get current attraction to check for old image
        $stmt = $this->conn->prepare("SELECT image_url FROM attractions WHERE id = ?");
        $stmt->execute([$id]);
        $currentAttraction = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Update the record
        $stmt = $this->conn->prepare("UPDATE attractions SET name = ?, location = ?, description = ?, category = ?, image_url = ?, is_top_destination = ?, display_order = ? WHERE id = ?");
        $result = $stmt->execute([
            $data['name'],
            $data['location'],
            $data['description'] ?? '',
            $data['category'] ?? 'city',
            $data['image_url'] ?? null,
            $data['is_top_destination'] ?? 0,
            $data['display_order'] ?? 0,
            $id
        ]);
        
        // Delete old image if it exists and new image is different
        if ($result && $currentAttraction && $data['image_url'] && $currentAttraction['image_url'] && $currentAttraction['image_url'] !== $data['image_url']) {
            $oldImagePath = __DIR__ . '/../../' . $currentAttraction['image_url'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        
        return $result;
    }

    public function getThreeCards() {
        $stmt = $this->conn->prepare("SELECT * FROM three_cards ORDER BY display_order, id LIMIT 3");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllThreeCards() {
        $stmt = $this->conn->prepare("SELECT * FROM three_cards ORDER BY display_order, id");
        $stmt->execute();
        $cards = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Enforce 3-card limit - return only first 3 cards
        return array_slice($cards, 0, 3);
    }

    public function getThreeCard($id) {
        $stmt = $this->conn->prepare("SELECT * FROM three_cards WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addThreeCard($data) {
        // Check current count to enforce 3-card limit
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM three_cards");
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($result['count'] >= 3) {
            throw new \Exception("Cannot add more than 3 cards. Limit reached.");
        }
        
        $stmt = $this->conn->prepare("INSERT INTO three_cards (title, description, image_url, display_order) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? '',
            $data['image_url'] ?? '',
            $data['display_order'] ?? 0
        ]);
    }

    public function updateThreeCard($id, $data) {
        // Get current card to check for old image
        $stmt = $this->conn->prepare("SELECT image_url FROM three_cards WHERE id = ?");
        $stmt->execute([$id]);
        $currentCard = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Update the record
        $stmt = $this->conn->prepare("UPDATE three_cards SET title = ?, description = ?, image_url = ?, display_order = ? WHERE id = ?");
        $result = $stmt->execute([
            $data['title'],
            $data['description'] ?? '',
            $data['image_url'] ?? '',
            $data['display_order'] ?? 0,
            $id
        ]);
        
        // Delete old image if it exists and new image is different
        if ($result && $currentCard && $data['image_url'] && $currentCard['image_url'] && $currentCard['image_url'] !== $data['image_url']) {
            $oldImagePath = __DIR__ . '/../../' . $currentCard['image_url'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        
        return $result;
    }

    public function deleteThreeCard($id) {
        // First get the card to find the image file
        $stmt = $this->conn->prepare("SELECT image_url FROM three_cards WHERE id = ?");
        $stmt->execute([$id]);
        $card = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Delete the record
        $stmt = $this->conn->prepare("DELETE FROM three_cards WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        // Delete the image file if it exists
        if ($result && $card && $card['image_url']) {
            $imagePath = __DIR__ . '/../../' . $card['image_url'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        return $result;
    }

    public function delete($id) {
        // First get the attraction to find the image file
        $stmt = $this->conn->prepare("SELECT image_url FROM attractions WHERE id = ?");
        $stmt->execute([$id]);
        $attraction = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Delete the record
        $stmt = $this->conn->prepare("DELETE FROM attractions WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        // Delete the image file if it exists
        if ($result && $attraction && $attraction['image_url']) {
            $imagePath = __DIR__ . '/../../' . $attraction['image_url'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        return $result;
    }
}
?>
