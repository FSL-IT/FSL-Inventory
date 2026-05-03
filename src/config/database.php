<?php
// Note: In production, load these from a .env file.
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'fsl_inventory');
define('DB_USER', 'root');
define('DB_PASS', ''); // Update with your local password

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                self::$connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Log error internally, do not echo to the user in production
                error_log("Database Connection Error: " . $e->getMessage());
                die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
            }
        }
        return self::$connection;
    }
}