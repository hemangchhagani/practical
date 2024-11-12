<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Utils\JWTUtils;

use App\Lib\Config;


class AuthService
{
    public $userRepository;
    public $secretKey = ''; // 

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

     // Login and generate token
    public function loginservice($email, $password)
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {

            $payload = [
            'iat' => time(),                // Issued at: current time
            'exp' => time() + 3600,         // Expiration time: current time + 1 hour
            'data' => [
                'id' => $user['id'],
                'email' => $user['email']
            ]
        ];




        $jwt = JWTUtils::encode($payload,$this->secretKey);
        $response =  array('token' => $jwt , 'user_details' => $payload );
        return $response;   
    }

}

    // // Register a new user
public function register($username, $email, $password)
{
    if ($this->userRepository->emailExists($email)) {
        $response =  array('error' => 'Email already exists' );
        return $response;

    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    if ($this->userRepository->createUser($username, $email, $hashedPassword)) {
        $response =  array('message' => 'User registered successfully' );
        return $response;
    }

    $response =  array('error' => 'User registration failed');
    return $response;

}

    /**
     * Validate the JWT token and return user ID if valid
     *
     * @param string $token
     * @return mixed User ID if valid, null otherwise
     */



    public static function validateToken($token)
    {
        try {
        // Fetch secret key from Config
            $secretKey = Config::get('jwt_secret_key');
            if (!$secretKey) {
                throw new \Exception('JWT secret key not configured');
            }

        // Decode the token
            $decoded = JWTUtils::decode($token, $secretKey);

        // Check if an error message was returned
            if (is_array($decoded) && isset($decoded['status']) && $decoded['status'] === 'error') {
                throw new \Exception($decoded['message']);
            }

        // Return user ID if token is valid
            return $decoded->data->id;
        } catch (\Exception $e) {
        // Log or handle the error as needed
            return null;
        }
    }

}
