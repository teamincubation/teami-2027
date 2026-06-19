<?php
header('Content-Type: text/html; charset=utf-8');
echo "<h2>Database Connection Diagnostic Test</h2>";

$host = 'localhost';
$db   = 'u806388046_new_teami2027';
$user = 'u806388046_newteami2027';

$passwords = [
    'Teami@2027#Incroot$)',
    'Teami@2027#Incroot$',
    'Teami@2027#Incroot',
    '7iB&Uiqz' // Default password from u806388046_default
];

echo "<h3>Parameters:</h3>";
echo "<ul>";
echo "<li>Host: <code>$host</code></li>";
echo "<li>Database: <code>$db</code></li>";
echo "<li>Username: <code>$user</code></li>";
echo "</ul>";

$success = false;

foreach ($passwords as $pass) {
    echo "<p>Testing password: <code>" . htmlspecialchars($pass) . "</code>... ";
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<strong style='color:green;'>SUCCESS!</strong> Connection established successfully.</p>";
        
        // Let's check table count
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<p>Found " . count($tables) . " tables in database: " . implode(', ', $tables) . "</p>";
        
        $success = true;
        break;
    } catch (PDOException $e) {
        echo "<span style='color:red;'>FAILED</span> (Error: " . htmlspecialchars($e->getMessage()) . ")</p>";
    }
}

if (!$success) {
    echo "<h3 style='color:red;'>❌ Database connection could not be established with any of the passwords.</h3>";
    echo "<p>Please verify in Hostinger hPanel:</p>";
    echo "<ol>";
    echo "<li>That the MySQL User <code>u806388046_newteami2027</code> is associated with Database <code>u806388046_new_teami2027</code>.</li>";
    echo "<li>That you have set the password for user <code>u806388046_newteami2027</code> to exactly match one of the tested passwords.</li>";
    echo "</ol>";
}
