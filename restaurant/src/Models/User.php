<?php

namespace App\Models;

use PDO;

class User extends BaseModel
{
    protected $table = 'users'; // Set the table name for the User model

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    // Find user by email
    public function getByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user (overrides the create method to handle password hashing)
    public function createUser($username, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $this->create([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
        ]);
    }

    // Verify user password
    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
}
