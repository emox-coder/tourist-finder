<?php
/**
 * Unit Tests for ManageAttraction UseCase
 */

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../Helpers/TestCase.php';

use App\UseCases\ManageAttraction;

class ManageAttractionTest extends TestCase {
    private $useCase;
    private $db;
    
    public function setUp(): void {
        $this->useCase = new ManageAttraction();
        $this->db = getTestDbConnection();
    }
    
    public function testUseCaseCanBeInstantiated(): void {
        $this->assertInstanceOf('App\UseCases\ManageAttraction', $this->useCase);
    }
    
    public function testCanGetAllAttractions(): void {
        $attractions = $this->useCase->getAll();
        $this->assertIsArray($attractions);
    }
    
    public function testCanGetTopDestinations(): void {
        $destinations = $this->useCase->getTopDestinations();
        $this->assertIsArray($destinations);
    }
    
    public function testCanGetThreeCards(): void {
        $cards = $this->useCase->getThreeCards();
        $this->assertIsArray($cards);
    }
    
    public function testCanGetAllThreeCards(): void {
        $cards = $this->useCase->getAllThreeCards();
        $this->assertIsArray($cards);
        $this->assertLessThanOrEqual(3, count($cards));
    }
    
    public function testCanCreateAttraction(): void {
        $testData = [
            'name' => 'UseCase Test Attraction ' . time(),
            'location' => 'Test Location',
            'description' => 'Test Description',
            'category' => 'city'
        ];
        
        $result = $this->useCase->create($testData);
        $this->assertTrue($result);
        
        // Cleanup
        $stmt = $this->db->prepare("DELETE FROM attractions WHERE name LIKE ?");
        $stmt->execute([$testData['name'] . '%']);
    }
    
    public function testCanGetAttractionById(): void {
        // Create test attraction
        $testData = [
            'name' => 'UseCase Test By ID ' . time(),
            'location' => 'Test Location',
            'description' => 'Test Description',
            'category' => 'city'
        ];
        
        $this->useCase->create($testData);
        
        // Get the ID
        $stmt = $this->db->prepare("SELECT id FROM attractions WHERE name LIKE ?");
        $stmt->execute([$testData['name'] . '%']);
        $attraction = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($attraction) {
            $result = $this->useCase->getById($attraction['id']);
            $this->assertIsArray($result);
            $this->assertEquals($testData['name'], $result['name']);
            
            // Cleanup
            $stmt = $this->db->prepare("DELETE FROM attractions WHERE id = ?");
            $stmt->execute([$attraction['id']]);
        } else {
            $this->fail('Could not find test attraction');
        }
    }
    
    public function testCanUpdateAttraction(): void {
        // Create test attraction
        $testData = [
            'name' => 'UseCase Update Test ' . time(),
            'location' => 'Original Location',
            'description' => 'Original Description',
            'category' => 'city'
        ];
        
        $this->useCase->create($testData);
        
        // Get the ID
        $stmt = $this->db->prepare("SELECT id FROM attractions WHERE name LIKE ?");
        $stmt->execute([$testData['name'] . '%']);
        $attraction = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($attraction) {
            // Update
            $updateData = [
                'name' => 'Updated Name',
                'location' => 'Updated Location',
                'description' => 'Updated Description',
                'category' => 'municipality'
            ];
            
            $result = $this->useCase->update($attraction['id'], $updateData);
            $this->assertTrue($result);
            
            // Verify
            $updated = $this->useCase->getById($attraction['id']);
            $this->assertEquals('Updated Name', $updated['name']);
            $this->assertEquals('Updated Location', $updated['location']);
            $this->assertEquals('municipality', $updated['category']);
            
            // Cleanup
            $stmt = $this->db->prepare("DELETE FROM attractions WHERE id = ?");
            $stmt->execute([$attraction['id']]);
        } else {
            $this->fail('Could not find test attraction');
        }
    }
    
    public function testCanDeleteAttraction(): void {
        // Create test attraction
        $testData = [
            'name' => 'UseCase Delete Test ' . time(),
            'location' => 'Test Location',
            'description' => 'Test Description',
            'category' => 'city'
        ];
        
        $this->useCase->create($testData);
        
        // Get the ID
        $stmt = $this->db->prepare("SELECT id FROM attractions WHERE name LIKE ?");
        $stmt->execute([$testData['name'] . '%']);
        $attraction = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($attraction) {
            $result = $this->useCase->delete($attraction['id']);
            $this->assertTrue($result);
            
            // Verify deletion
            $deleted = $this->useCase->getById($attraction['id']);
            $this->assertNull($deleted);
        } else {
            $this->fail('Could not find test attraction');
        }
    }
    
    public function testThreeCardLimitIsEnforced(): void {
        // Get current count
        $cards = $this->useCase->getAllThreeCards();
        
        if (count($cards) >= 3) {
            // If limit is reached, try to add one more (should fail)
            $testData = [
                'title' => 'Should Fail Card ' . time(),
                'description' => 'Test',
                'image_url' => null,
                'display_order' => 0
            ];
            
            try {
                $this->useCase->addThreeCard($testData);
                $this->fail('Expected exception when adding card beyond limit');
            } catch (Exception $e) {
                $this->pass('Correctly threw exception when limit reached');
            }
        } else {
            echo "  ⊘ Skipped: Three cards limit not reached\n";
        }
    }
}

// Run tests
echo "\n=== Running ManageAttraction UseCase Tests ===\n";
$test = new ManageAttractionTest();
$test->setUp();
$test->testUseCaseCanBeInstantiated();
$test->testCanGetAllAttractions();
$test->testCanGetTopDestinations();
$test->testCanGetThreeCards();
$test->testCanGetAllThreeCards();
$test->testCanCreateAttraction();
$test->testCanGetAttractionById();
$test->testCanUpdateAttraction();
$test->testCanDeleteAttraction();
$test->testThreeCardLimitIsEnforced();