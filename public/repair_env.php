<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

// Simple authorization code to prevent unauthorized access
$securityToken = 'TeamIncubation2026!';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['security_token'] ?? '';
    if ($token !== $securityToken) {
        $error = 'Invalid security token.';
    } else {
        $dbPass = $_POST['db_pass'] ?? '';
        $googleId = $_POST['google_id'] ?? '';
        $googleSecret = $_POST['google_secret'] ?? '';
        
        if (empty($dbPass) || empty($googleId) || empty($googleSecret)) {
            $error = 'All configuration fields are required.';
        } else {
            $envPath = dirname(__DIR__) . '/.env';
            
            $envContent = <<<ENV
# App Configuration
APP_NAME="Team Incubation"
APP_ENV=production
APP_DEBUG=false
APP_KEY=some_random_secret_32_characters_long
APP_URL=https://teamincubation.in

# Database Settings
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=u806388046_new_teami2027
DB_USER=u806388046_newteami2027
DB_PASS='{$dbPass}'

# Mail Configuration (Hostinger SMTP)
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USER=office@teamincubation.in
MAIL_PASS=smtp_password_here
MAIL_FROM_ADDRESS=office@teamincubation.in
MAIL_FROM_NAME="Team Incubation"

# Google OAuth Credentials
GOOGLE_CLIENT_ID="{$googleId}"
GOOGLE_CLIENT_SECRET="{$googleSecret}"
GOOGLE_REDIRECT_URL="https://teamincubation.in/auth/google/callback"
ENV;

            if (file_put_contents($envPath, $envContent)) {
                $success = '✅ .env file successfully created/updated!<br>';
                
                // Try database setup
                try {
                    $pdo = new PDO("mysql:host=localhost;dbname=u806388046_new_teami2027;charset=utf8mb4", "u806388046_newteami2027", $dbPass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $success .= '✅ Database connection successful!<br>';
                    
                    $schemaPath = dirname(__DIR__) . '/database/schema.sql';
                    if (file_exists($schemaPath)) {
                        $sql = file_get_contents($schemaPath);
                        $pdo->exec($sql);
                        $success .= '✅ Database schema imported successfully.<br>';
                    }
                    
                    // Check if roles are already seeded to prevent duplicate key errors
                    $stmtRoles = $pdo->query("SELECT COUNT(*) FROM roles");
                    $roleCount = (int)$stmtRoles->fetchColumn();
                    if ($roleCount === 0) {
                        $sampleDataPath = dirname(__DIR__) . '/database/sample-data.sql';
                        if (file_exists($sampleDataPath)) {
                            $sql = file_get_contents($sampleDataPath);
                            $pdo->exec($sql);
                            $success .= '✅ Sample data imported successfully.<br>';
                        }
                    } else {
                        $success .= 'ℹ️ Sample data already imported.<br>';
                    }
                    
                    // Admin user check
                    $stmt = $pdo->query("SELECT * FROM users WHERE email = 'incubation.ngo@gmail.com'");
                    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (!$userRow) {
                        $password = 'Admin@123';
                        $hash = password_hash($password, PASSWORD_BCRYPT);
                        $stmtIns = $pdo->prepare("INSERT INTO users (email, password_hash, role_id, status, email_verified_at) VALUES (?, ?, 1, 'active', CURRENT_TIMESTAMP)");
                        $stmtIns->execute(['incubation.ngo@gmail.com', $hash]);
                        $userId = $pdo->lastInsertId();

                        $stmtProf = $pdo->prepare("INSERT INTO profiles (user_id, full_name, mobile) VALUES (?, ?, ?)");
                        $stmtProf->execute([$userId, 'Super Administrator', '9876543210']);
                        $success .= '✅ Super Admin account generated: incubation.ngo@gmail.com / Admin@123<br>';
                    }
                    
                    $success .= '🚀 <strong>System configured and ready!</strong>';
                } catch (PDOException $e) {
                    $error = 'Database connection failed: ' . $e->getMessage();
                }
            } else {
                $error = 'Failed to write to .env file. Please check directory permissions.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Platform Configuration Tool</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #f3f4f6; padding: 2rem; color: #374151; }
        .card { max-width: 500px; margin: 2rem auto; background: #fff; padding: 2.5rem; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); }
        h2 { margin-top: 0; color: #111827; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.75rem; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.95rem; }
        input[type="text"], input[type="password"] { width: 100%; padding: 0.65rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 0.95rem; }
        input[type="text"]:focus, input[type="password"]:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
        button { background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; font-size: 1rem; margin-top: 0.5rem; transition: background 0.2s; }
        button:hover { background: #2563eb; }
        .error { color: #dc2626; background: #fee2e2; padding: 1rem; border-radius: 6px; margin-bottom: 1.25rem; border: 1px solid #fca5a5; font-size: 0.95rem; }
        .success { color: #16a34a; background: #dcfce7; padding: 1rem; border-radius: 6px; margin-bottom: 1.25rem; border: 1px solid #86efac; font-size: 0.95rem; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="card">
        <h2>System Configuration & Repair</h2>
        <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1.5rem;">Enter the security token and your configuration keys to safely write the environment file and initialize the database.</p>
        
        <?php if ($error): ?>
            <div class="error"><strong>Error:</strong> <?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="security_token">Security Token</label>
                <input type="password" id="security_token" name="security_token" required placeholder="Enter security token">
            </div>
            <div class="form-group">
                <label for="db_pass">Database Password</label>
                <input type="password" id="db_pass" name="db_pass" required placeholder="Enter DB Password">
            </div>
            <div class="form-group">
                <label for="google_id">Google Client ID</label>
                <input type="text" id="google_id" name="google_id" required placeholder="Enter Google Client ID">
            </div>
            <div class="form-group">
                <label for="google_secret">Google Client Secret</label>
                <input type="password" id="google_secret" name="google_secret" required placeholder="Enter Google Client Secret">
            </div>
            <button type="submit">Save & Run Setup</button>
        </form>
    </div>
</body>
</html>
