<?php
/**
 * PSR-4 Autoloading Compliance Verification Tool
 * 
 * Verifies that all PHP files in Backend/ follow PSR-4 autoloading standards:
 * 1. Have proper namespace declarations
 * 2. Namespace matches directory structure and composer.json mappings
 * 3. Class/interface/trait names match file names
 * 4. No hardcoded require_once or include_once statements remain
 * 
 * Usage: php verify-psr4.php [--detailed] [--fix]
 */

class PSR4Verifier {
    private $projectRoot;
    private $composerConfig;
    private $results = [];
    private $detailed = false;
    
    public function __construct($projectRoot, $detailed = false) {
        $this->projectRoot = $projectRoot;
        $this->detailed = $detailed;
        $this->loadComposerConfig();
    }
    
    private function loadComposerConfig() {
        $composerPath = $this->projectRoot . '/composer.json';
        if (!file_exists($composerPath)) {
            $this->error("composer.json not found at: $composerPath");
            exit(1);
        }
        
        $json = json_decode(file_get_contents($composerPath), true);
        if (!isset($json['autoload']['psr-4'])) {
            $this->error("No PSR-4 configuration found in composer.json");
            exit(1);
        }
        
        $this->composerConfig = $json['autoload']['psr-4'];
    }
    
    public function verify() {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "PSR-4 AUTOLOADING COMPLIANCE VERIFICATION\n";
        echo str_repeat("=", 80) . "\n\n";
        
        $this->log("Project Root: {$this->projectRoot}");
        $this->log("PSR-4 Mappings Found: " . count($this->composerConfig) . "\n");
        
        foreach ($this->composerConfig as $namespace => $basePath) {
            $fullPath = $this->projectRoot . '/' . $basePath;
            $this->log("Scanning: $namespace → $basePath");
            $this->scanDirectory($fullPath, $namespace, $basePath);
        }
        
        $this->generateReport();
    }
    
    private function scanDirectory($dir, $namespace, $basePath) {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = $this->getAllPhpFiles($dir);
        foreach ($files as $filePath) {
            $this->verifyFile($filePath, $namespace, $basePath);
        }
    }
    
    private function getAllPhpFiles($dir) {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getRealPath();
            }
        }
        
        return $files;
    }
    
    private function verifyFile($filePath, $namespace, $basePath) {
        $relativePath = str_replace($this->projectRoot . '\\' . $basePath, '', str_replace('\\', '/', $filePath));
        $relativePath = str_replace($this->projectRoot . '/' . $basePath, '', $relativePath);
        
        $result = [
            'file' => $filePath,
            'relativePath' => $relativePath,
            'namespace' => $namespace,
            'basePath' => $basePath,
            'issues' => [],
            'warnings' => [],
        ];
        
        // Read file content
        $content = file_get_contents($filePath);
        
        // Extract namespace declaration
        $declaredNamespace = $this->extractNamespace($content);
        $result['declaredNamespace'] = $declaredNamespace;
        
        // Extract class/interface/trait names
        $definitions = $this->extractDefinitions($content);
        $result['definitions'] = $definitions;
        
        // Check for hardcoded requires
        $requires = $this->extractRequires($content);
        $result['requires'] = $requires;
        
        // Calculate expected namespace
        $expectedNamespace = $this->calculateExpectedNamespace($filePath, $namespace, $basePath);
        $result['expectedNamespace'] = $expectedNamespace;
        
        // Validate
        $this->validateFile($result);
        
        $this->results[] = $result;
    }
    
    private function extractNamespace($content) {
        if (preg_match('/namespace\s+([A-Za-z0-9_\\\\]+)\s*;/m', $content, $matches)) {
            return $matches[1];
        }
        return null;
    }
    
    private function extractDefinitions($content) {
        $defs = [];
        
        // Extract class names
        if (preg_match_all('/^\s*(abstract\s+)?class\s+([A-Za-z_][A-Za-z0-9_]*)/m', $content, $matches)) {
            foreach ($matches[2] as $class) {
                $defs['class'][] = $class;
            }
        }
        
        // Extract interface names
        if (preg_match_all('/^\s*interface\s+([A-Za-z_][A-Za-z0-9_]*)/m', $content, $matches)) {
            foreach ($matches[1] as $interface) {
                $defs['interface'][] = $interface;
            }
        }
        
        // Extract trait names
        if (preg_match_all('/^\s*trait\s+([A-Za-z_][A-Za-z0-9_]*)/m', $content, $matches)) {
            foreach ($matches[1] as $trait) {
                $defs['trait'][] = $trait;
            }
        }
        
        return $defs;
    }
    
    private function extractRequires($content) {
        $requires = [];
        if (preg_match_all('/(require_once|include_once|require|include)\s+([^;]+);/m', $content, $matches)) {
            foreach ($matches[0] as $match) {
                $requires[] = $match;
            }
        }
        return $requires;
    }
    
    private function calculateExpectedNamespace($filePath, $namespace, $basePath) {
        // Get relative path from base path
        $baseDir = realpath($this->projectRoot . '/' . $basePath);
        $realPath = realpath($filePath);
        
        // Get relative path from base directory
        $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $realPath);
        $relativePath = str_replace('\\', '/', $relativePath);
        
        // Remove .php extension
        $relativePath = substr($relativePath, 0, -4);
        
        // Convert path separators to namespace backslashes
        $pathToNamespace = str_replace('/', '\\', $relativePath);
        
        // Combine namespace with path
        $expected = rtrim($namespace, '\\') . '\\' . $pathToNamespace;
        
        return $expected;
    }
    
    private function validateFile(&$result) {
        $namespace = $result['declaredNamespace'];
        $expected = $result['expectedNamespace'];
        $definitions = $result['definitions'];
        $requires = $result['requires'];
        $filePath = basename($result['file']);
        
        // Check 1: Namespace declaration exists
        if ($namespace === null) {
            $result['issues'][] = "❌ MISSING NAMESPACE - File has no namespace declaration";
        }
        
        // Check 2: Namespace matches directory structure (NOT including class name)
        // Extract just the namespace part (without the class name)
        $expectedNamespaceOnly = substr($expected, 0, strrpos($expected, '\\'));
        
        if ($namespace !== null && $namespace !== $expectedNamespaceOnly) {
            $result['issues'][] = "❌ WRONG NAMESPACE - Declared: '$namespace', Expected: '$expectedNamespaceOnly'";
        }
        
        // Check 3: File name matches definition name
        $fileName = substr($filePath, 0, -4); // Remove .php
        $classNames = $definitions['class'] ?? [];
        $interfaceNames = $definitions['interface'] ?? [];
        $traitNames = $definitions['trait'] ?? [];
        $allNames = array_merge($classNames, $interfaceNames, $traitNames);
        
        if (count($allNames) === 0) {
            $result['warnings'][] = "⚠️  No class/interface/trait definitions found in file";
        } elseif (!in_array($fileName, $allNames)) {
            $result['issues'][] = "❌ NAME MISMATCH - File name '$fileName' doesn't match any definition: " . implode(', ', $allNames);
        }
        
        // Check 4: No hardcoded requires
        if (!empty($requires)) {
            foreach ($requires as $req) {
                // Filter out vendor/autoload.php and config files which are acceptable
                if (strpos($req, 'vendor/autoload.php') === false && 
                    strpos($req, 'vendor\\autoload.php') === false &&
                    strpos($req, 'config/config.php') === false &&
                    strpos($req, 'config\\config.php') === false) {
                    $result['issues'][] = "❌ HARDCODED REQUIRE - Found: $req";
                }
            }
        }
    }
    
    private function generateReport() {
        $totalFiles = count($this->results);
        $compliantFiles = 0;
        $filesWithIssues = [];
        
        foreach ($this->results as $result) {
            if (empty($result['issues'])) {
                $compliantFiles++;
            } else {
                $filesWithIssues[] = $result;
            }
        }
        
        // Summary
        echo "\n" . str_repeat("-", 80) . "\n";
        echo "SUMMARY\n";
        echo str_repeat("-", 80) . "\n";
        printf("Total PHP Files Scanned: %d\n", $totalFiles);
        printf("✅ Compliant Files: %d\n", $compliantFiles);
        printf("❌ Files with Issues: %d\n", count($filesWithIssues));
        printf("Compliance Rate: %.1f%%\n\n", ($totalFiles > 0) ? ($compliantFiles / $totalFiles) * 100 : 0);
        
        // Compliant files
        if ($compliantFiles > 0 && $this->detailed) {
            echo str_repeat("-", 80) . "\n";
            echo "✅ COMPLIANT FILES\n";
            echo str_repeat("-", 80) . "\n";
            foreach ($this->results as $result) {
                if (empty($result['issues'])) {
                    $this->log("✅ " . $result['relativePath']);
                }
            }
            echo "\n";
        }
        
        // Files with issues
        if (!empty($filesWithIssues)) {
            echo str_repeat("-", 80) . "\n";
            echo "❌ FILES WITH ISSUES\n";
            echo str_repeat("-", 80) . "\n";
            foreach ($filesWithIssues as $result) {
                $expectedNamespaceOnly = substr($result['expectedNamespace'], 0, strrpos($result['expectedNamespace'], '\\'));
                echo "\nFile: " . $result['relativePath'] . "\n";
                echo "Expected Namespace: " . $expectedNamespaceOnly . "\n";
                echo "Declared Namespace: " . ($result['declaredNamespace'] ?? '(MISSING)') . "\n";
                
                foreach ($result['issues'] as $issue) {
                    echo "  " . $issue . "\n";
                }
                
                if (!empty($result['warnings'])) {
                    foreach ($result['warnings'] as $warning) {
                        echo "  " . $warning . "\n";
                    }
                }
            }
        }
        
        // Detailed listing if requested
        if ($this->detailed && $totalFiles > 0) {
            echo "\n" . str_repeat("-", 80) . "\n";
            echo "DETAILED FILE LISTING\n";
            echo str_repeat("-", 80) . "\n";
            foreach ($this->results as $result) {
                $status = empty($result['issues']) ? '✅' : '❌';
                $expectedNamespaceOnly = substr($result['expectedNamespace'], 0, strrpos($result['expectedNamespace'], '\\'));
                echo "\n$status " . $result['relativePath'] . "\n";
                echo "  Namespace: " . ($result['declaredNamespace'] ?? '(MISSING)') . "\n";
                echo "  Expected:  " . $expectedNamespaceOnly . "\n";
                if (!empty($result['definitions'])) {
                    foreach ($result['definitions'] as $type => $items) {
                        if (!empty($items)) {
                            echo "  Defines:   " . ucfirst($type) . "(s): " . implode(', ', $items) . "\n";
                        }
                    }
                }
            }
        }
        
        echo "\n" . str_repeat("=", 80) . "\n";
        
        // Exit with code
        if (count($filesWithIssues) === 0) {
            $this->success("ALL FILES COMPLIANT WITH PSR-4!");
            exit(0);
        } else {
            $this->error("COMPLIANCE ISSUES FOUND - See details above");
            exit(1);
        }
    }
    
    private function log($message) {
        echo $message . "\n";
    }
    
    private function error($message) {
        echo "\n❌ ERROR: $message\n\n";
    }
    
    private function success($message) {
        echo "\n✅ SUCCESS: $message\n\n";
    }
}

// Main execution
$projectRoot = dirname(__DIR__);
$detailed = in_array('--detailed', $argv);

$verifier = new PSR4Verifier($projectRoot, $detailed);
$verifier->verify();
