<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

abstract class BaseModel {
    protected static ?PDO $db = null;

    /**
     * Get the PDO database connection.
     */
    public static function getConnection(): PDO {
        if (self::$db !== null) {
            return self::$db;
        }

        try {
            $config = require dirname(dirname(__DIR__)) . '/config/database.php';
            $driver = $config['default'] ?? 'mysql';
            
            if ($driver === 'mysql') {
                $mysql = $config['connections']['mysql'];
                $dsn = "mysql:host={$mysql['host']};port={$mysql['port']};dbname={$mysql['database']};charset={$mysql['charset']}";
                try {
                    self::$db = new PDO($dsn, $mysql['username'], $mysql['password'], $config['options']);
                    self::$db->exec("SET time_zone = '+05:30'");
                } catch (PDOException $e) {
                    if (config('app.env') === 'local') {
                        $driver = 'sqlite';
                    } else {
                        throw $e;
                    }
                }
            }
            
            if ($driver === 'sqlite') {
                $sqlite = $config['connections']['sqlite'];
                $dbFile = $sqlite['database'];
                $dir = dirname($dbFile);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                $dsn = "sqlite:{$dbFile}";
                $options = $config['options'] ?? [];
                unset($options[PDO::MYSQL_ATTR_INIT_COMMAND]);
                self::$db = new PDO($dsn, null, null, $options);
                self::$db->exec("PRAGMA foreign_keys = ON;");
            }
            
            return self::$db;
        } catch (PDOException $e) {
            logMessage('CRITICAL', "Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failure: " . $e->getMessage());
        }
    }

    /**
     * Run a prepared query statement.
     */
    protected function query(string $sql, array $params = []): \PDOStatement {
        $pdo = self::getConnection();
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            logMessage('ERROR', "Query failed: {$sql} | Message: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fetch all matching rows.
     */
    protected function select(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Fetch a single matching row.
     */
    protected function selectOne(string $sql, array $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Helper to insert a record into a table.
     */
    protected function insert(string $table, array $data): string {
        $columns = implode(', ', array_map(fn($col) => "`{$col}`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})";
        
        $this->query($sql, array_values($data));
        return self::getConnection()->lastInsertId();
    }

    /**
     * Helper to update records.
     */
    protected function update(string $table, array $data, string $where, array $whereParams = []): int {
        $setClauses = implode(', ', array_map(fn($col) => "`{$col}` = ?", array_keys($data)));
        $sql = "UPDATE `{$table}` SET {$setClauses} WHERE {$where}";
        
        $params = array_merge(array_values($data), $whereParams);
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Helper to delete records.
     */
    protected function delete(string $table, string $where, array $whereParams = []): int {
        $sql = "DELETE FROM `{$table}` WHERE {$where}";
        $stmt = $this->query($sql, $whereParams);
        return $stmt->rowCount();
    }

    /**
     * Transaction helpers.
     */
    public static function beginTransaction(): void {
        self::getConnection()->beginTransaction();
    }

    public static function commit(): void {
        self::getConnection()->commit();
    }

    public static function rollBack(): void {
        self::getConnection()->rollBack();
    }
}
