<?php
// Standalone Setup Script for Hostinger

$envContent = <<<'ENV'
# App Configuration
APP_NAME="Team Incubation"
APP_ENV=production
APP_DEBUG=false
APP_KEY=some_random_secret_32_characters_long
APP_URL=https://teamincubation.in

# Database Settings
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=u806388046_new_teami2027
DB_USER=u806388046_newteami2027
DB_PASS="Teami@2027#Incroot$)"

# Mail Configuration (Hostinger SMTP)
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USER=info@teamincubation.in
MAIL_PASS=smtp_password_here
MAIL_FROM_ADDRESS=info@teamincubation.in
MAIL_FROM_NAME="Team Incubation"
ENV;

$envPath = dirname(__DIR__) . '/.env';
file_put_contents($envPath, $envContent);
echo "<h3>✅ .env file created successfully!</h3>";

// Now import the database schema
$host = '127.0.0.1';
$db   = 'u806388046_new_teami2027';
$user = 'u806388046_newteami2027';
$pass = 'Teami@2027#Incroot$)';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $schemaPath = dirname(__DIR__) . '/database/schema.sql';
    if (file_exists($schemaPath)) {
        $sql = file_get_contents($schemaPath);
        // Execute schema statements individually if necessary, but exec() usually works
        $pdo->exec($sql);
        echo "<h3>✅ Database schema imported successfully!</h3>";
    }

    $sampleDataPath = dirname(__DIR__) . '/database/sample-data.sql';
    if (file_exists($sampleDataPath)) {
        $sql = file_get_contents($sampleDataPath);
        $pdo->exec($sql);
        echo "<h3>✅ Sample data imported successfully!</h3>";
    }

    echo "<h3>🚀 Setup Complete!</h3>";

} catch (PDOException $e) {
    echo "<h3>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</h3>";
}

// Self destruct
@unlink(__FILE__);
echo "<p>🗑️ Setup script automatically deleted for security.</p>";
echo "<p><a href='/'>Go to Website</a></p>";
