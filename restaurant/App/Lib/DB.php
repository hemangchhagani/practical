<?php
namespace App\Lib;

use PDO;
use PDOException;

class DB
{
    private static $pdo;

    /**
     * Initialize the database connection using configuration
     */
    public static function connect()
    {
        if (!self::$pdo) {
            try {
                $dsn = 'mysql:host=' . Config::get('database.host') . ';dbname=' . Config::get('database.name');
                self::$pdo = new PDO($dsn, Config::get('database.user'), Config::get('database.password'));
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database Connection Error: " . $e->getMessage());
            }
        }
    }

    /**
     * Execute a SQL query with optional parameters
     *
     * @param string $query
     * @param array $params
     * @return PDOStatement|false
     */
    public static function query($query, $params = [])
    {
        self::connect();
        $stmt = self::$pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Close the database connection
     */
    public static function close()
    {
        self::$pdo = null;
    }
}
