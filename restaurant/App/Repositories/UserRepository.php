<?php

namespace App\Repositories;


use App\Database\Connection;
use PDO;


class UserRepository
{
     protected $db;

    public function __construct()
    {
        $this->db = new Connection();
    }

    // Find a user by email
    public function findByEmail($email)
    {
        
        $query = "SELECT id, email, password FROM users WHERE email = :email";
        // Use getConnection() to get the PDO instance
        $stmt = $this->db->getConnection()->prepare($query);

        if ($stmt === false) {
            throw new PDOException('Failed to prepare the SQL statement.');
        }

        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Check if email exists
    public function emailExists($email)
    {
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt =  $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Add a new user
    public function createUser($username, $email, $hashedPassword)
    {
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt =  $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        return $stmt->execute();
    }
}
