<?php

namespace App\Controllers;

use App\Utils\JWTUtils; // Correctly reference JWTUtils here
use PDO;

use App\Services\AuthService;
use App\Lib\Request;
use App\Lib\Response;

class AuthController
{

   private $authService;
   private $response;

   public function __construct()
   {
    //$this->authService = new AuthService();
    $this->response = new Response();
}

public function login(array $params)
{
        // Validate input parameters
        $identifier = $params['identifier'] ?? null; // Username or email
        $password = $params['password'] ?? null;

        if (!$identifier || !$password) {
            return $this->response->toJSON(['status' => 'failed', 'msg' => 'Identifier and password are required'], 400);
        }

        // Attempt to login
        $token = $this->authService->login($identifier, $password);

        if ($token) {
            return $this->response->toJSON(['status' => 'success', 'token' => $token]);
        } else {
            return $this->response->toJSON(['status' => 'failed', 'msg' => 'Invalid credentials'], 401);
        }
    }


    // Register new user
    public function register()
    {
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $username = $input['username'] ?? null;
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
        $query = "INSERT INTO users (username,email,password) VALUES (:username,:email,:password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
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
