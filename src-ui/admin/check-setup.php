<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Setup Check - TAF</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        .check-item {
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .check-item.success {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }
        .check-item.error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        .check-item.warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .icon {
            font-size: 24px;
            width: 30px;
            text-align: center;
        }
        .success .icon { color: #28a745; }
        .error .icon { color: #dc3545; }
        .warning .icon { color: #ffc107; }
        .message {
            flex: 1;
            color: #2c3e50;
        }
        .actions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 5px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Admin Setup Check</h1>
        <p class="subtitle">Checking if your admin dashboard is ready to use...</p>

        <?php
        $allOk = true;
        $checks = [];

        // Check 1: PHP version
        $phpVersion = phpversion();
        if (version_compare($phpVersion, '7.0.0', '>=')) {
            $checks[] = ['success', '✓', "PHP version is good ($phpVersion)"];
        } else {
            $checks[] = ['error', '✗', "PHP version too old ($phpVersion). Need PHP 7.0+"];
            $allOk = false;
        }

        // Check 2: PDO extension
        if (extension_loaded('pdo_mysql')) {
            $checks[] = ['success', '✓', "PDO MySQL extension is loaded"];
        } else {
            $checks[] = ['error', '✗', "PDO MySQL extension not loaded. Enable it in php.ini"];
            $allOk = false;
        }

        // Check 3: Database connection
        try {
            $config = require __DIR__ . '/../Backend/config/config.php';
            $pdo = new PDO(
                "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']}",
                $config['db']['user'],
                $config['db']['pass']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $checks[] = ['success', '✓', "Database connection successful"];
        } catch (PDOException $e) {
            $checks[] = ['error', '✗', "Database connection failed: " . $e->getMessage()];
            $allOk = false;
        }

        // Check 4: Admins table
        if (isset($pdo)) {
            try {
                $stmt = $pdo->query("SHOW TABLES LIKE 'admins'");
                if ($stmt->rowCount() > 0) {
                    $checks[] = ['success', '✓', "Admins table exists"];
                    
                    // Check for default admin
                    $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
                    $count = $stmt->fetchColumn();
                    if ($count > 0) {
                        $checks[] = ['success', '✓', "Admin account exists ($count admin(s))"];
                    } else {
                        $checks[] = ['warning', '⚠', "Admins table is empty. No admin accounts!"];
                        $allOk = false;
                    }
                } else {
                    $checks[] = ['error', '✗', "Admins table does not exist"];
                    $allOk = false;
                }
            } catch (PDOException $e) {
                $checks[] = ['error', '✗', "Error checking admins table: " . $e->getMessage()];
                $allOk = false;
            }

            // Check 5: Attractions table
            try {
                $stmt = $pdo->query("SHOW TABLES LIKE 'attractions'");
                if ($stmt->rowCount() > 0) {
                    $checks[] = ['success', '✓', "Attractions table exists"];
                    
                    // Check for required columns
                    $stmt = $pdo->query("DESCRIBE attractions");
                    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    $requiredColumns = ['name', 'location', 'image_url', 'is_top_destination', 'display_order'];
                    $missingColumns = [];
                    foreach ($requiredColumns as $col) {
                        if (!in_array($col, $columns)) {
                            $missingColumns[] = $col;
                        }
                    }
                    
                    if (empty($missingColumns)) {
                        $checks[] = ['success', '✓', "All required columns exist in attractions table"];
                    } else {
                        $checks[] = ['error', '✗', "Missing columns: " . implode(', ', $missingColumns) . 
                                   ". Run the database migration!"];
                        $allOk = false;
                    }
                } else {
                    $checks[] = ['error', '✗', "Attractions table does not exist"];
                    $allOk = false;
                }
            } catch (PDOException $e) {
                $checks[] = ['error', '✗', "Error checking attractions table: " . $e->getMessage()];
                $allOk = false;
            }
        }

        // Check 6: Upload directory
        $uploadDir = __DIR__ . '/../assets/img/destinations/';
        if (is_dir($uploadDir)) {
            if (is_writable($uploadDir)) {
                $checks[] = ['success', '✓', "Upload directory exists and is writable"];
            } else {
                $checks[] = ['warning', '⚠', "Upload directory exists but is not writable"];
            }
        } else {
            $checks[] = ['warning', '⚠', "Upload directory does not exist. Will be created on first upload"];
        }

        // Check 7: .htaccess (if Apache)
        if (function_exists('apache_get_modules') || str_contains($_SERVER['SERVER_SOFTWARE'] ?? '', 'Apache')) {
            if (file_exists(__DIR__ . '/../.htaccess')) {
                $checks[] = ['success', '✓', ".htaccess file found"];
            } else {
                $checks[] = ['warning', '⚠', ".htaccess file not found. May need URL rewriting configuration"];
            }
        }

        // Display checks
        foreach ($checks as $check) {
            echo "<div class='check-item {$check[0]}'>";
            echo "<span class='icon'>{$check[1]}</span>";
            echo "<span class='message'>{$check[2]}</span>";
            echo "</div>";
        }

        echo "<div class='actions'>";
        if ($allOk) {
            echo "<p style='color: #28a745; margin-bottom: 15px;'><strong>✓ All checks passed! Your admin dashboard is ready.</strong></p>";
            echo "<a href='login.php' class='btn btn-primary'>Go to Login Page</a>";
        } else {
            echo "<p style='color: #dc3545; margin-bottom: 15px;'><strong>✗ Some checks failed. Please fix the issues above.</strong></p>";
            echo "<a href='check-setup.php' class='btn btn-secondary'>Re-check</a>";
            echo "<a href='../Backend/database_updates.sql' class='btn btn-primary' download>Download SQL Migration</a>";
        }
        echo "</div>";
        ?>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
            <h3 style="color: #2c3e50; margin-bottom: 10px;">Default Login Credentials:</h3>
            <p style="color: #7f8c8d; margin-bottom: 5px;">
                <strong>Email:</strong> <code>admin@example.com</code>
            </p>
            <p style="color: #7f8c8d; margin-bottom: 15px;">
                <strong>Password:</strong> <code>password123</code>
            </p>
            <p style="color: #e74c3c; font-size: 14px;">
                ⚠️ <strong>IMPORTANT:</strong> Change the default password after first login!
            </p>
        </div>
    </div>
</body>
</html>