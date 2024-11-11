<?php

namespace App\Database;

use PDO;
use PDOException;

// use Dotenv\Dotenv;


// // Load environment variables
// $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
// $dotenv->load();



class Connection
{
    private $host = 'localhost';
    private $dbName ='restaurants';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function __construct()
    {
        $this->connect();
    }

    // Connect to the database using PDO
    private function connect()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbName}",
                $this->username,
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Expose the PDO instance
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
