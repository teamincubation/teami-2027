<?php

namespace App\Controllers;

class HomeController extends BaseController {
    
    public function index(): void {
        $db = \App\Models\BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM banners WHERE active = 1 AND display_location = 'home_hero' ORDER BY display_order ASC");
        $banners = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('home', [
            'title' => 'Team Incubation | Empowering Through Education & Care',
            'active' => 'home',
            'banners' => $banners
        ]);
    }

    public function about(): void {
        $db = \App\Models\BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM legacy_milestones WHERE active = 1 ORDER BY year ASC, display_order ASC");
        $milestones = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('about', [
            'title' => 'About Us | Our Mission & Journey',
            'active' => 'about',
            'milestones' => $milestones
        ]);
    }

    public function contact(): void {
        $this->render('contact', [
            'title' => 'Contact Us | Reach out to Team Incubation',
            'active' => 'contact'
        ]);
    }

    public function setup(): void {
        $host = 'localhost';
        $db   = 'u806388046_new_teami2027';
        $user = 'u806388046_newteami2027';

        $passwordsToTry = [
            'Inc@27root$Admin#',
            'Teami@2027#Incroot$)',
            'Teami@2027#Incroot$',
            'Teami@2027#Incroot'
        ];

        $pdo = null;
        $successfulPassword = null;
        $errors = [];

        foreach ($passwordsToTry as $pwd) {
            try {
                $pdo = new \PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pwd);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $successfulPassword = $pwd;
                break;
            } catch (\PDOException $e) {
                $errors[$pwd] = $e->getMessage();
            }
        }

        if (!$pdo) {
            echo "<h3>❌ Database Connection Failed for all tried passwords:</h3>";
            foreach ($errors as $pwd => $err) {
                echo "<p><strong>Password:</strong> " . htmlspecialchars($pwd) . " <br><strong>Error:</strong> " . htmlspecialchars($err) . "</p>";
            }
            return;
        }

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
DB_PASS='{$successfulPassword}'

# Mail Configuration (Hostinger SMTP)
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USER=office@teamincubation.in
MAIL_PASS=smtp_password_here
MAIL_FROM_ADDRESS=office@teamincubation.in
MAIL_FROM_NAME="Team Incubation"
ENV;

        $envPath = dirname(dirname(__DIR__)) . '/.env';
        file_put_contents($envPath, $envContent);
        echo "<h3>✅ .env file created successfully!</h3>";

        try {
            
            $schemaPath = dirname(dirname(__DIR__)) . '/database/schema.sql';
            if (file_exists($schemaPath)) {
                $sql = file_get_contents($schemaPath);
                $pdo->exec($sql);
                echo "<h3>✅ Database schema imported successfully!</h3>";
            }

            $sampleDataPath = dirname(dirname(__DIR__)) . '/database/sample-data.sql';
            if (file_exists($sampleDataPath)) {
                $sql = file_get_contents($sampleDataPath);
                $pdo->exec($sql);
                echo "<h3>✅ Sample data imported successfully!</h3>";
            }

            $stmt = $pdo->query("SELECT * FROM users WHERE email = 'incubation.ngo@gmail.com'");
            $userRow = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$userRow) {
                $password = 'Admin@123';
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmtIns = $pdo->prepare("INSERT INTO users (role_id, prefix, first_name, last_name, mobile, email, password_hash, account_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmtIns->execute([1, 'Mr.', 'Super', 'Admin', '9876543210', 'incubation.ngo@gmail.com', $hash, 'active']);
                echo "<h3>✅ Admin Account Generated: incubation.ngo@gmail.com</h3>";
            }

            echo "<h3>🚀 Setup Complete! You can now visit the homepage.</h3>";

        } catch (\PDOException $e) {
            echo "<h3>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</h3>";
        }
    }
}
