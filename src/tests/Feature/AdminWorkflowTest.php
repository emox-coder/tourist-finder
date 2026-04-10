<?php
/**
 * Feature Tests for Admin Workflows
 */

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../Helpers/TestCase.php';

class AdminWorkflowTest extends TestCase {
    private $apiBase;
    private $adminPagePath;
    
    public function setUp(): void {
        $this->apiBase = 'http://localhost/Tourist-Attraction-Finder-Web/Backend/routes/api.php';
        $this->adminPagePath = BASE_PATH . '/admin/';
    }
    
    public function testAdminPagesExist(): void {
        $pages = [
            'index.php',
            'login.php',
            'logout.php',
            'top-destinations.php',
            'three-cards.php',
            'attractions.php',
            'admin-accounts.php',
            'upload.php'
        ];
        
        foreach ($pages as $page) {
            $this->assertFileExists($this->adminPagePath . $page);
        }
    }
    
    public function testAdminLoginPageExists(): void {
        $this->assertFileExists($this->adminPagePath . 'login.php');
    }
    
    public function testAdminAuthIncludeExists(): void {
        $this->assertFileExists($this->adminPagePath . 'includes/auth.php');
    }
    
    public function testAdminTopDestinationsPageStructure(): void {
        $content = file_get_contents($this->adminPagePath . 'top-destinations.php');
        
        $this->assertStringContainsString('Manage Top Destinations', $content);
        $this->assertStringContainsString('API_BASE', $content);
        $this->assertStringContainsString('loadDestinations', $content);
    }
    
    public function testAdminThreeCardsPageExists(): void {
        $this->assertFileExists($this->adminPagePath . 'three-cards.php');
    }
    
    public function testAdminAttractionsPageExists(): void {
        $this->assertFileExists($this->adminPagePath . 'attractions.php');
    }
    
    public function testAdminAccountsPageExists(): void {
        $this->assertFileExists($this->adminPagePath . 'admin-accounts.php');
    }
    
    public function testUploadHandlerExists(): void {
        $this->assertFileExists($this->adminPagePath . 'upload.php');
    }
    
    public function testAdminPagesRequireAuth(): void {
        $content = file_get_contents($this->adminPagePath . 'top-destinations.php');
        
        // Check if page includes auth
        $this->assertStringContainsString('requireLogin()', $content);
    }
}

// Run tests
echo "\n=== Running Admin Workflow Tests ===\n";
$test = new AdminWorkflowTest();
$test->setUp();
$test->testAdminPagesExist();
$test->testAdminLoginPageExists();
$test->testAdminAuthIncludeExists();
$test->testAdminTopDestinationsPageStructure();
$test->testAdminThreeCardsPageExists();
$test->testAdminAttractionsPageExists();
$test->testAdminAccountsPageExists();
$test->testUploadHandlerExists();
$test->testAdminPagesRequireAuth();