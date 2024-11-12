<?php

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{

    public AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    // Login
    public function login()
    {
        try {
            
            $input = json_decode(file_get_contents('php://input'), true);
            $email = $input['email'] ?? null;
            $password = $input['password'] ?? null;

            if (!$email || !$password) {
                //http_response_code(400);
                $result = array(['message' => 'Email and password are required'] , 'status' => 400 );
                return $result;
            }
            
            $token = $this->authService->loginservice($email,$password);
            
            if ($token) {
                //http_response_code(200);
                $result = array('message' => 'Login successful', 'data' => $token  , 'status' => 200 );
                return $result;
            } else {
                // http_response_code(401);
                $result = array('message' => 'Invalid credentials' , 'status' => 401 );
                return $result;
                
                
            }
        } catch (Exception $e) {
            //http_response_code(500);
            $result = array('message' =>  'An error occurred: ' . $e->getMessage() , 'status' => 500);
            return $result;
        }
    }

    // Register
    public function register()
    {
        try {

            $input = json_decode(file_get_contents('php://input'), true);
            $username = $input['username'] ?? null;
            $email = $input['email'] ?? null;
            $password = $input['password'] ?? null;

            if (!$email || !$password) {
                //http_response_code(400);
                $result = array('message' =>   'Email and password are required' , 'status' => 400);
                return $result;

                return;
            }

            $result = $this->authService->register($username, $email, $password);
            
            if (isset($result['error'])) {
                http_response_code(409);
                //echo json_encode(['message' => $result['error']]);
                $result = array('message' =>   $result['error'] , 'status' => 409);
                return $result;

            } else {    
                 http_response_code(201);
                $result = array('message' =>   $result['message'] , 'status' => 201);
                return $result;
            }
        } catch (Exception $e) {
                http_response_code(500);
            $result = array('message' =>  'An error occurred: ' . $e->getMessage() , 'status' => 500);
            return $result;
        }
    }
}
