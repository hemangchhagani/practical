<?php

namespace App\Controllers;

use App\Utils\JWTUtils; // Correctly reference JWTUtils here
use PDO;

class AuthController
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function login()
    {
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? null;
        $password = $input['password'] ?? null;

        // Check if email and password are provided
        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(['message' => 'Email and password are required']);
            return;
        }

        // Query to get user by email
        $query = "SELECT id, email, password FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Successfully authenticated
            $userId = $user['id'];
            $email = $user['email'];

            // Define your secret key and expiration time
            $secretKey = 'your-secret-key';
            $expiration = 3600; // 1 hour

            // Generate JWT token using JWTUtils
            $token = JWTUtils::generateToken([
                'user_id' => $userId,
                'email' => $email
            ], $secretKey, $expiration);

            // Return token as response
            echo json_encode([
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email']
                ]
            ]);

        } else {
            // Invalid email or password
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
        }
    }


    // Register new user
    public function register()
    {
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? null;
        $password = $input['password'] ?? null;

        // Check if email and password are provided
        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(['message' => 'Email and password are required']);
            return;
        }

        // Check if the email already exists in the database
        $checkQuery = "SELECT COUNT(*) FROM users WHERE email = :email";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();
        $emailExists = $checkStmt->fetchColumn();

        if ($emailExists) {
            http_response_code(409); // Conflict
            echo json_encode(['message' => 'Email already exists']);
            return;
        }

        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $query = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            // Registration successful
            http_response_code(201);
            echo json_encode(['message' => 'User registered successfully']);
        } else {
            // Registration failed
            http_response_code(500);
            echo json_encode(['message' => 'User registration failed']);
        }
    }

}
