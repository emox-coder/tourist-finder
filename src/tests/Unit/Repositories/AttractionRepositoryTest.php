<?php
/**
 * Unit Tests for AttractionRepositoryImpl
 */

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../Helpers/TestCase.php';

use Infrastructure\Repositories\AttractionRepositoryImpl;

class AttractionRepositoryTest extends TestCase {
    private $repository;
    private $db;
    
    public function setUp(): void {
        $this->repository = new AttractionRepositoryImpl();
        $this->db = getTestDbConnection();
    }
    
    public function testRepositoryCanBeInstantiated(): void {
        $this->assertInstanceOf('Infrastructure\Repositories\AttractionRepositoryImpl', $this->repository);
    }
    
    public function testCanGetAllAttractions(): void {
        $attractions = $this->repository->getAll();
        $this->assertIsArray($attractions);
    }
    
    public function testCanGetTopDestinations(): void {
        $destinations = $this->repository->getTopDestinations();
        $this->assertIsArray($destinations);
    }
    
    public function testCanGetThreeCards(): void {
        $cards = $this->repository->getThreeCards();
        $this->assertIsArray($cards);
        $this->assertLessThanOrEqual(3, count($cards));
    }
    
    public function testCanGetAllThreeCards(): void {
        $cards = $this->repository->getAllThreeCards();
        $this->assertIsArray($cards);
        $this->assertLessThanOrEqual(3, count($cards));
    }
    
    public function testCanCreateAttraction(): void {
        $testData = [
            'name' => 'Test Attraction ' . time(),
            'location' => 'Test Location',
            'description' => 'Test Description',
            'category' => 'city',
            'image_url' => null,
            'is_top_destination' => 0,
            'display_order' => 0
        ];
        
        $result = $this->repository->create($testData);
        $this->assertTrue($result);
        
        // Cleanup
        $this->cleanupTestAttraction($testData['name']);
    }
    
    public function testCanGetAttractionById(): void {
        // First create a test attraction
        $testData = [
            'name' => 'Test Attraction By ID ' . time(),
            'location' => 'Test Location',
            'description' => 'Test Description',
            'category' => 'city'
        ];
        
        $this->repository->create($testData);
        
        // Get the ID of the created attraction
        $stmt = $this->db->prepare("SELECT id FROM attractions WHERE name = ?");
        $stmt->execute([$testData['name']]);
        $attraction = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($attraction) {
            $result = $this->repository->getById($attraction['id']);
            $this->assertIsArray($result);
            $this->assertEquals($testData['name'], $result['name']);
            
            // Cleanup
            $this->cleanupTestAttraction($testData['name']);
        } else {
            $this->fail('Could not find test attraction');
        }
    }
    
    public function testCanUpdateAttraction(): void {
        // Create test attraction
        $testData = [
            'name' => 'Test Attraction Update ' . time(),
            'location' => 'Original Location',
            'description' => 'Original Description',
            'category' => 'city'
        ];
        
        $this->repository->create($testData);
        
        // Get the ID
        $stmt = $this->db->prepare("SELECT id FROM attractions WHERE name = ?");
        $stmt->execute([$testData['name']]);
        $attraction = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($attraction) {
            // Update the attraction
            $updateData = [
                'name' => 'Updated Attraction',
                'location' => 'Updated Location',
                'description' => 'Updated Description',
                'category' => 'municipality'
            ];
            
            $result = $this->repository->update($attraction['id'], $updateData);
            $this->assertTrue($result);
            
            // Verify update
            $updated = $this->repository->getById($attraction['id']);
            $this->assertEquals('Updated Attraction', $updated['name']);
            $this->assertEquals('Updated Location', $updated['location']);
            $this->assertEquals('municipality', $updated['category']);
            
            // Cleanup
            $this->cleanupTestAttraction('Updated Attraction');
        } else {
            $this->fail('Could not find test attraction');
        }
    }
    
    public function testCanDeleteAttraction(): void {
        // Create test attraction
        $testData = [
            'name' => 'Test Attraction Delete ' . time(),
            'location' => 'Test Location',
            'description' => 'Test Description',
            'category' => 'city'
        ];
        
        $this->repository->create($testData);
        
        // Get the ID
        $stmt = $this->db->prepare("SELECT id FROM attractions WHERE name = ?");
        $stmt->execute([$testData['name']]);
        $attraction = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($attraction) {
            // Delete the attraction
            $result = $this->repository->delete($attraction['id']);
            $this->assertTrue($result);
            
            // Verify deletion
            $deleted = $this->repository->getById($attraction['id']);
            $this->assertNull($deleted);
        } else {
            $this->fail('Could not find test attraction');
        }
    }
    
    public function testCanCreateThreeCard(): void {
        // First, check if we can add more cards (limit is 3)
        $currentCards = $this->repository->getAllThreeCards();
        
        if (count($currentCards) < 3) {
            $testData = [
                'title' => 'Test Card ' . time(),
                'description' => 'Test Description',
                'image_url' => null,
                'display_order' => 0
            ];
            
            $result = $this->repository->addThreeCard($testData);
            $this->assertTrue($result);
            
            // Cleanup
            $this->cleanupTestThreeCard($testData['title']);
        } else {
            echo "  ⊘ Skipped: Three cards limit reached\n";
        }
    }
    
    public function testCanUpdateThreeCard(): void {
        // Get an existing card or create one
        $cards = $this->repository->getAllThreeCards();
        
        if (count($cards) > 0) {
            $card = $cards[0];
            
            $updateData = [
                'title' => 'Updated Card Title',
                'description' => 'Updated Description',
                'image_url' => null,
                'display_order' => 0
            ];
            
            $result = $this->repository->updateThreeCard($card['id'], $updateData);
            $this->assertTrue($result);
            
            // Verify update
            $updated = $this->repository->getThreeCard($card['id']);
            $this->assertEquals('Updated Card Title', $updated['title']);
            
            // Restore original title
            $originalData = [
                'title' => $card['title'],
                'description' => $card['description'],
                'image_url' => $card['image_url'],
                'display_order' => $card['display_order']
            ];
            $this->repository->updateThreeCard($card['id'], $originalData);
        } else {
            echo "  ⊘ Skipped: No three cards available\n";
        }
    }
    
    public function testCanDeleteThreeCard(): void {
        // Create a test card if possible
        $currentCards = $this->repository->getAllThreeCards();
        
        if (count($currentCards) < 3) {
            $testData = [
                'title' => 'Test Card Delete ' . time(),
                'description' => 'Test Description',
                'image_url' => null,
                'display_order' => 0
            ];
            
            $this->repository->addThreeCard($testData);
            
            // Get the ID
            $stmt = $this->db->prepare("SELECT id FROM three_cards WHERE title = ?");
            $stmt->execute([$testData['title']]);
            $card = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($card) {
                $result = $this->repository->deleteThreeCard($card['id']);
                $this->assertTrue($result);
                
                // Verify deletion
                $deleted = $this->repository->getThreeCard($card['id']);
                $this->assertNull($deleted);
            } else {
                $this->fail('Could not find test three card');
            }
        } else {
            echo "  ⊘ Skipped: Three cards limit reached\n";
        }
    }
    
    /**
     * Helper method to cleanup test attractions
     */
    private function cleanupTestAttraction($name) {
        $stmt = $this->db->prepare("DELETE FROM attractions WHERE name LIKE ?");
        $stmt->execute([$name . '%']);
    }
    
    /**
     * Helper method to cleanup test three cards
     */
    private function cleanupTestThreeCard($title) {
        $stmt = $this->db->prepare("DELETE FROM three_cards WHERE title LIKE ?");
        $stmt->execute([$title . '%']);
    }
}

// Run tests
echo "\n=== Running AttractionRepository Tests ===\n";
$test = new AttractionRepositoryTest();
$test->setUp();
$test->testRepositoryCanBeInstantiated();
$test->testCanGetAllAttractions();
$test->testCanGetTopDestinations();
$test->testCanGetThreeCards();
$test->testCanGetAllThreeCards();
$test->testCanCreateAttraction();
$test->testCanGetAttractionById();
$test->testCanUpdateAttraction();
$test->testCanDeleteAttraction();
$test->testCanCreateThreeCard();
$test->testCanUpdateThreeCard();
$test->testCanDeleteThreeCard();
