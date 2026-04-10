<?php
/**
 * Main Test Runner Script
 * Run all tests with: php run-tests.php
 */

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║     Tourist Attraction Finder - Test Suite Runner       ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Define base path
define('BASE_PATH', __DIR__);

// Load bootstrap
require_once __DIR__ . '/tests/bootstrap.php';
require_once __DIR__ . '/tests/Helpers/TestCase.php';

// Track overall results
$overallPassed = 0;
$overallFailed = 0;
$testSuites = [];

/**
 * Run a test file and capture results
 */
function runTestFile($file, $suiteName) {
    global $overallPassed, $overallFailed, $testSuites;
    
    echo "\n";
    echo "─────────────────────────────────────────────────────────\n";
    echo " Suite: $suiteName\n";
    echo " File: $file\n";
    echo "─────────────────────────────────────────────────────────\n";
    
    $startTime = microtime(true);
    
    // Reset stats
    TestCase::resetStats();
    
    // Include the test file (it runs tests on include)
    require_once $file;
    
    $endTime = microtime(true);
    $duration = round($endTime - $startTime, 3);
    
    $stats = TestCase::getStats();
    $passed = $stats['passed'];
    $failed = $stats['failed'];
    $total = $stats['total'];
    
    $overallPassed += $passed;
    $overallFailed += $failed;
    
    $testSuites[] = [
        'name' => $suiteName,
        'file' => $file,
        'passed' => $passed,
        'failed' => $failed,
        'total' => $total,
        'duration' => $duration
    ];
    
    echo "\n";
    echo "  Results: $passed/$total passed, $failed failed ({$duration}s)\n";
}

// Run Unit Tests
runTestFile(__DIR__ . '/tests/Unit/Repositories/AttractionRepositoryTest.php', 'Unit Tests - Repositories');
runTestFile(__DIR__ . '/tests/Unit/UseCases/ManageAttractionTest.php', 'Unit Tests - UseCases');

// Run Integration Tests
runTestFile(__DIR__ . '/tests/Integration/ApiTest.php', 'Integration Tests - API');

// Run Feature Tests
runTestFile(__DIR__ . '/tests/Feature/AdminWorkflowTest.php', 'Feature Tests - Admin Workflows');

// Print Summary
echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║                    TEST SUMMARY                          ║\n";
echo "╠══════════════════════════════════════════════════════════╣\n";

foreach ($testSuites as $suite) {
    $status = $suite['failed'] == 0 ? '✓ PASS' : '✗ FAIL';
    printf("║ %-30s %s (%d/%d) ║\n", 
        substr($suite['name'], 0, 30), 
        $status, 
        $suite['passed'], 
        $suite['total']
    );
}

echo "╠══════════════════════════════════════════════════════════╣\n";
$totalTests = $overallPassed + $overallFailed;
$overallStatus = $overallFailed == 0 ? '✓ ALL TESTS PASSED' : '✗ SOME TESTS FAILED';
printf("║ %-57s ║\n", "Total: $totalTests tests");
printf("║ %-57s ║\n", "Passed: $overallPassed");
printf("║ %-57s ║\n", "Failed: $overallFailed");
printf("║ %-57s ║\n", "Result: $overallStatus");
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Exit with appropriate code
exit($overallFailed > 0 ? 1 : 0);