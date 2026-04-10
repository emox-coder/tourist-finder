<?php
// Test API from admin directory perspective
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Testing API from admin directory...\n";

// This is the path the JavaScript uses from admin directory
$apiUrl = '../Backend/routes/api.php?uri=/api/admin/admins';

try {
    // Use file_get_contents to test the API
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Content-Type: application/json'
        ]
    ]);
    
    $response = file_get_contents($apiUrl, false, $context);
    
    echo "API Response: " . $response . "\n";
    
    if ($response) {
        $data = json_decode($response, true);
        echo "JSON decode: " . ($data ? "OK" : "FAILED") . "\n";
        
        if ($data) {
            echo "Success: " . ($data['success'] ?? 'unknown') . "\n";
            if (isset($data['error'])) {
                echo "Error: " . $data['error'] . "\n";
            }
        }
    } else {
        echo "No response received\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?>
