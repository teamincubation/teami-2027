<?php

// Prevent direct front controller execution bypass, define entry
define('BOOTSTRAPPED', true);

// Setup autoloading and bootstrap
require_once dirname(__DIR__) . '/bootstrap/app.php';

use AppConfig as Config;

$isCli = (php_sapi_name() === 'cli');
$lockFile = Config::get('app.paths.private_storage') . '/installed.lock';

// Helper to check if already installed
function isInstalled(string $lockFile): bool {
    return file_exists($lockFile);
}

// Helper to translate MySQL queries to SQLite format
function translateMysqlToSqlite(string $sql): string {
    // 0. Translate escaped single quotes to SQLite double-single quotes
    $sql = str_replace("\\'", "''", $sql);
    
    // 1. Convert SET FOREIGN_KEY_CHECKS to PRAGMA
    $sql = str_ireplace('SET FOREIGN_KEY_CHECKS = 0', 'PRAGMA foreign_keys = OFF', $sql);
    $sql = str_ireplace('SET FOREIGN_KEY_CHECKS = 1', 'PRAGMA foreign_keys = ON', $sql);
    
    // 2. Remove MySQL table storage engine and collation settings
    $sql = preg_replace('/\) ENGINE\s*=\s*InnoDB[^;]*/i', ')', $sql);
    
    // 3. Convert MySQL auto increment primary keys to SQLite style
    $sql = preg_replace('/`id` INT[^,]*AUTO_INCREMENT[^,]*/i', '`id` INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
    $sql = preg_replace('/`id` INT PRIMARY KEY AUTO_INCREMENT/i', '`id` INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
    
    // 4. Convert ENUM declarations to TEXT
    $sql = preg_replace('/ENUM\s*\([^)]+\)/i', 'TEXT', $sql);
    
    // 5. Remove ON UPDATE CURRENT_TIMESTAMP modifier
    $sql = str_ireplace('ON UPDATE CURRENT_TIMESTAMP', '', $sql);
    
    // 6. Strip out inline MySQL INDEX and KEY definitions from CREATE TABLE
    $lines = explode("\n", $sql);
    $cleanedLines = [];
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (preg_match('/^INDEX\s/i', $trimmed) || preg_match('/^KEY\s/i', $trimmed)) {
            continue;
        }
        if (preg_match('/^UNIQUE KEY\s+`?(\w+)`?\s*\(([^)]+)\)/i', $trimmed, $m)) {
            $cleanedLines[] = "    UNIQUE (" . $m[2] . "),";
            continue;
        }
        $cleanedLines[] = $line;
    }
    $sql = implode("\n", $cleanedLines);
    
    // Clean up trailing commas in column lists
    $sql = preg_replace('/,\s*\)/s', "\n)", $sql);
    
    return $sql;
}

// Helper to execute SQL script
function executeSqlFile(PDO $pdo, string $filePath): array {
    if (!file_exists($filePath)) {
        return ['success' => false, 'message' => "SQL file not found at " . basename($filePath)];
    }

    $sql = file_get_contents($filePath);
    
    // Check if the connection driver is SQLite and translate if needed
    $isSqlite = ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite');
    if ($isSqlite) {
        $sql = translateMysqlToSqlite($sql);
    }
    
    // Remove comments
    $sql = preg_replace('/--.*\n/', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Split into individual queries
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    $results = [];
    $pdo->beginTransaction();
    try {
        foreach ($queries as $query) {
            if (empty($query)) continue;
            $pdo->exec($query);
        }
        $pdo->commit();
        return ['success' => true, 'message' => "Successfully executed " . basename($filePath)];
    } catch (PDOException $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => "Error in " . basename($filePath) . ": " . $e->getMessage()];
    }
}

// ----------------------------------------------------
// 1. CLI Execution Handler
// ----------------------------------------------------
if ($isCli) {
    echo "--- Team Incubation NGO Platform CLI Migrator ---\n";
    
    if (isInstalled($lockFile)) {
        echo "Error: Installation is locked. Delete 'installed.lock' in private persistent storage to re-run.\n";
        exit(1);
    }
    
    // Validate secret
    $envSecret = Config::get('app.setup_secret');
    echo "Enter Setup Secret: ";
    $handle = fopen("php://stdin", "r");
    $inputSecret = trim(fgets($handle));
    $inputSecret = str_replace("\xef\xbb\xbf", '', $inputSecret);
    
    if ($inputSecret !== $envSecret) {
        echo "Error: Invalid Setup Secret.\n";
        exit(1);
    }
    
    // Admin details
    echo "Enter default Super Admin Email: ";
    $adminEmail = trim(fgets($handle));
    echo "Enter default Super Admin Mobile: ";
    $adminMobile = trim(fgets($handle));
    echo "Enter default Super Admin Password: ";
    $adminPass = trim(fgets($handle));
    
    if (empty($adminEmail) || empty($adminMobile) || empty($adminPass)) {
        echo "Error: Email, Mobile, and Password are required.\n";
        exit(1);
    }
    
    try {
        $dbConfig = require dirname(__DIR__) . '/config/database.php';
        $driver = $dbConfig['default'] ?? 'mysql';
        if ($driver === 'mysql') {
            $mysql = $dbConfig['connections']['mysql'];
            $dsn = "mysql:host={$mysql['host']};port={$mysql['port']};dbname={$mysql['database']};charset={$mysql['charset']}";
            try {
                $pdo = new PDO($dsn, $mysql['username'], $mysql['password'], $dbConfig['options']);
                $pdo->exec("SET time_zone = '+05:30'");
            } catch (PDOException $e) {
                if (config('app.env') === 'local') {
                    $driver = 'sqlite';
                } else {
                    throw $e;
                }
            }
        }
        if ($driver === 'sqlite') {
            $sqlite = $dbConfig['connections']['sqlite'];
            $dbFile = $sqlite['database'];
            $dir = dirname($dbFile);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $dsn = "sqlite:{$dbFile}";
            $options = $dbConfig['options'] ?? [];
            unset($options[PDO::MYSQL_ATTR_INIT_COMMAND]);
            $pdo = new PDO($dsn, null, null, $options);
            $pdo->exec("PRAGMA foreign_keys = ON;");
        }
        
        echo "Running database schema creation...\n";
        $schemaRes = executeSqlFile($pdo, __DIR__ . '/schema.sql');
        echo $schemaRes['message'] . "\n";
        if (!$schemaRes['success']) exit(1);
        
        echo "Running sample data seeding...\n";
        $seedRes = executeSqlFile($pdo, __DIR__ . '/sample-data.sql');
        echo $seedRes['message'] . "\n";
        if (!$seedRes['success']) exit(1);
        
        // Insert Admin User
        $passHash = password_hash($adminPass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role_id, status, email_verified_at) VALUES (?, ?, 1, 'active', CURRENT_TIMESTAMP)");
        $stmt->execute([$adminEmail, $passHash]);
        $userId = $pdo->lastInsertId();
        
        $stmtProf = $pdo->prepare("INSERT INTO profiles (user_id, full_name, mobile) VALUES (?, ?, ?)");
        $stmtProf->execute([$userId, 'Super Administrator', $adminMobile]);
        
        // Write Lock File
        $lockDir = dirname($lockFile);
        if (!is_dir($lockDir)) {
            @mkdir($lockDir, 0755, true);
        }
        @file_put_contents($lockFile, "Installed on " . date('Y-m-d H:i:s'));
        echo "Installation completed successfully. Lock file written.\n";
        exit(0);
        
    } catch (Exception $e) {
        echo "Database connection or migration failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

// ----------------------------------------------------
// 2. Web Execution Handler (HTML View)
// ----------------------------------------------------
$errors = [];
$successMsg = null;
$installationLocked = isInstalled($lockFile);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$installationLocked) {
    $secret = $_POST['secret'] ?? '';
    $adminEmail = $_POST['email'] ?? '';
    $adminMobile = $_POST['mobile'] ?? '';
    $adminPass = $_POST['password'] ?? '';
    
    $envSecret = Config::get('app.setup_secret');
    
    if ($secret !== $envSecret) {
        $errors[] = "Invalid Setup Secret key.";
    }
    if (empty($adminEmail) || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please provide a valid administrator email.";
    }
    if (empty($adminMobile)) {
        $errors[] = "Please provide a contact mobile number.";
    }
    if (strlen($adminPass) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    
    if (empty($errors)) {
        try {
            $dbConfig = require dirname(__DIR__) . '/config/database.php';
            $driver = $dbConfig['default'] ?? 'mysql';
            if ($driver === 'mysql') {
                $mysql = $dbConfig['connections']['mysql'];
                $dsn = "mysql:host={$mysql['host']};port={$mysql['port']};dbname={$mysql['database']};charset={$mysql['charset']}";
                try {
                    $pdo = new PDO($dsn, $mysql['username'], $mysql['password'], $dbConfig['options']);
                    $pdo->exec("SET time_zone = '+05:30'");
                } catch (PDOException $e) {
                    if (config('app.env') === 'local') {
                        $driver = 'sqlite';
                    } else {
                        throw $e;
                    }
                }
            }
            if ($driver === 'sqlite') {
                $sqlite = $dbConfig['connections']['sqlite'];
                $dbFile = $sqlite['database'];
                $dir = dirname($dbFile);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                $dsn = "sqlite:{$dbFile}";
                $options = $dbConfig['options'] ?? [];
                unset($options[PDO::MYSQL_ATTR_INIT_COMMAND]);
                $pdo = new PDO($dsn, null, null, $options);
                $pdo->exec("PRAGMA foreign_keys = ON;");
            }
            
            // Execute schema
            $schemaRes = executeSqlFile($pdo, __DIR__ . '/schema.sql');
            if (!$schemaRes['success']) {
                throw new Exception($schemaRes['message']);
            }
            
            // Execute seeders
            $seedRes = executeSqlFile($pdo, __DIR__ . '/sample-data.sql');
            if (!$seedRes['success']) {
                throw new Exception($seedRes['message']);
            }
            
            // Create Admin Account (Super Admin has role_id = 1)
            $passHash = password_hash($adminPass, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role_id, status, email_verified_at) VALUES (?, ?, 1, 'active', CURRENT_TIMESTAMP)");
            $stmt->execute([$adminEmail, $passHash]);
            $userId = $pdo->lastInsertId();
            
            $stmtProf = $pdo->prepare("INSERT INTO profiles (user_id, full_name, mobile) VALUES (?, ?, ?)");
            $stmtProf->execute([$userId, 'Super Administrator', $adminMobile]);
            
            // Write locking file
            $lockDir = dirname($lockFile);
            if (!is_dir($lockDir)) {
                @mkdir($lockDir, 0755, true);
            }
            @file_put_contents($lockFile, "Installed via web interface on " . date('Y-m-d H:i:s'));
            $successMsg = "Database schema initialized, seed data loaded, and initial Super Admin created successfully! The installer is now locked.";
            $installationLocked = true;
            
        } catch (Exception $e) {
            $errors[] = "Migration failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Incubation Platform Web Installer</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 40px 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            background: #fff;
            margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 30px;
            border-top: 5px solid #14b8a6;
        }
        h2 { color: #111827; margin-top: 0; }
        p { color: #4b5563; font-size: 14px; line-height: 1.6; }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            color: #374151;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #f9fafb;
        }
        input:focus {
            outline: none;
            border-color: #14b8a6;
            background-color: #fff;
        }
        .btn {
            background-color: #14b8a6;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #0d9488;
        }
        .btn:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }
        .alert {
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .alert-success {
            background-color: #ccfbf1;
            color: #0f766e;
            border: 1px solid #99f6e4;
        }
        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Team Incubation Platform Web Installer</h2>
        
        <?php if ($installationLocked): ?>
            <div class="alert alert-warning">
                <strong>Installer Locked:</strong> The platform database has been initialized or a locking file exists. To reset, delete `installed.lock` from your private persistent storage files directory.
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Errors:</strong>
                <ul style="margin: 5px 0 0 15px; padding: 0;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($successMsg): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMsg) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="secret">Setup Authorization Secret Key</label>
                <input type="password" id="secret" name="secret" required placeholder="Enter SETUP_SECRET from your env variables" <?= $installationLocked ? 'disabled' : '' ?>>
            </div>
            
            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 25px 0;">
            <h3 style="font-size:16px; margin-bottom:15px; color:#111827;">Initial Super Administrator Details</h3>
            
            <div class="form-group">
                <label for="email">Admin Email Address</label>
                <input type="email" id="email" name="email" required placeholder="admin@teamincubation.in" <?= $installationLocked ? 'disabled' : '' ?>>
            </div>

            <div class="form-group">
                <label for="mobile">Admin Contact Phone</label>
                <input type="text" id="mobile" name="mobile" required placeholder="9876543210" <?= $installationLocked ? 'disabled' : '' ?>>
            </div>

            <div class="form-group">
                <label for="password">Admin Account Password (min 8 chars)</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" <?= $installationLocked ? 'disabled' : '' ?>>
            </div>
            
            <button type="submit" class="btn" <?= $installationLocked ? 'disabled' : '' ?>>Initialize Database & Setup Platform</button>
        </form>

        <div class="footer">
            Team Incubation NGO Core Architecture v1.0.0 &copy; 2026.
        </div>
    </div>
</body>
</html>
