<?php
/**
 * PSR-4 Autoloading Test
 * 
 * Verifies that PSR-4 autoloading is working correctly by instantiating
 * classes from each configured namespace without any require_once statements.
 */

echo "\n" . str_repeat("=", 80) . "\n";
echo "PSR-4 AUTOLOADING FUNCTIONALITY TEST\n";
echo str_repeat("=", 80) . "\n\n";

// Include Composer's PSR-4 autoloader
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("❌ ERROR: Composer autoloader not found at: $autoloadPath\n");
}

require_once $autoloadPath;
echo "✅ Composer autoloader loaded successfully\n\n";

// Test classes from App namespace
echo "Testing App\\Controllers namespace... ";
try {
    $controller = new App\Controllers\AdminController();
    echo "✅ AdminController instantiated\n";
} catch (Exception $e) {
    die("❌ FAILED: " . $e->getMessage() . "\n");
}

echo "Testing App\\Entities namespace... ";
try {
    $admin = new App\Entities\Admin();
    echo "✅ Admin instantiated\n";
} catch (Throwable $e) {
    echo "❌ FAILED: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

echo "Testing App\\Repositories namespace... ";
try {
    // Note: AdminRepository is an interface, so we'll just verify the class exists
    $refl = new ReflectionClass('App\Repositories\AdminRepository');
    echo "✅ AdminRepository interface loaded\n";
} catch (Exception $e) {
    die("❌ FAILED: " . $e->getMessage() . "\n");
}

echo "Testing App\\UseCases namespace... ";
try {
    $manageAdmin = new App\UseCases\ManageAdmin();
    echo "✅ ManageAdmin instantiated\n";
} catch (Exception $e) {
    die("❌ FAILED: " . $e->getMessage() . "\n");
}

// Test classes from Infrastructure namespace
echo "Testing Infrastructure\\Database namespace... ";
try {
    $database = new Infrastructure\Database\Database();
    echo "✅ Database instantiated\n";
} catch (Exception $e) {
    die("❌ FAILED: " . $e->getMessage() . "\n");
}

echo "Testing Infrastructure\\Repositories namespace... ";
try {
    $repo = new Infrastructure\Repositories\AdminRepositoryImpl();
    echo "✅ AdminRepositoryImpl instantiated\n";
} catch (Exception $e) {
    die("❌ FAILED: " . $e->getMessage() . "\n");
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "✅ SUCCESS: All PSR-4 autoloading tests passed!\n";
echo "   All classes from both namespaces loaded via autoloader without errors.\n";
echo str_repeat("=", 80) . "\n\n";
exit(0);
