<?php
/**
 * Integration Tests for API Endpoints
 */

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../Helpers/TestCase.php';

class ApiTest extends TestCase {
    private $apiBase;
    
    public function setUp(): void {
        $this->apiBase = 'http://localhost/Tourist-Attraction-Finder-Web/Backend/routes/api.php';
    }
    
    public function testApiEndpointExists(): void {
        $this->assertFileExists(BASE_PATH . '/Backend/routes/api.php');
    }
    
    public function testPublicTopDestinationsEndpoint(): void {
        $url = $this->apiBase . '?uri=/api/top-destinations';
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        
        $this->assertIsArray($data);
        $this->assertNotNull($data);
    }
    
    public function testPublicThreeCardsEndpoint(): void {
        $url = $this->apiBase . '?uri=/api/three-cards';
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        
        $this->assertIsArray($data);
        $this->assertNotNull($data);
    }
    
    public function testAdminAttractionsListEndpoint(): void {
        $url = $this->apiBase . '?uri=/api/admin/attractions';
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        
        $this->assertIsArray($data);
    }
    
    public function testApiResponseFormat(): void {
        $url = $this->apiBase . '?uri=/api/top-destinations';
        $response = file_get_contents($url);
        
        // Check if response is valid JSON
        json_decode($response);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }
    
    public function testApiHandlesInvalidEndpoint(): void {
        $url = $this->apiBase . '?uri=/api/invalid/endpoint';
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        
        $this->assertArrayHasKey('error', $data);
    }
}

// Run tests
echo "\n=== Running API Integration Tests ===\n";
$test = new ApiTest();
$test->setUp();
$test->testApiEndpointExists();
$test->testPublicTopDestinationsEndpoint();
$test->testPublicThreeCardsEndpoint();
$test->testAdminAttractionsListEndpoint();
$test->testApiResponseFormat();
$test->testApiHandlesInvalidEndpoint();