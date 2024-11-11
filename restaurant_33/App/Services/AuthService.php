<?php
namespace App\Services;

use App\Lib\DB;
use App\Lib\Logger;

class UserService
{
    public function getUserByIdentifier(string $identifier)
    {
        // Example method to retrieve user by email or username
        // Assuming there is a users table with columns email, username, and password
        $query = "SELECT * FROM users WHERE email = :identifier OR username = :identifier LIMIT 1";
        $stmt = DB::query($query, [':identifier' => $identifier]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function registerUser(array $data)
    {
        // Example registration code
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ];
        DB::query($query, $params);
        return DB::lastInsertId();
    }
}
