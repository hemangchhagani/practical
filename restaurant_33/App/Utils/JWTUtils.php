<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTUtils
{
    // Method to generate JWT token
    public static function generateToken($payload, $secretKey, $expiration)
    {
        $payload['exp'] = time() + $expiration;
        return JWT::encode($payload, $secretKey, 'HS256');
    }

    // Method to verify and decode JWT token
    public static function verifyToken($token, $secretKey)
    {
        try {
            return JWT::decode($token, new Key($secretKey, 'HS256'));
        } catch (\Exception $e) {
            return false; // Return false if verification fails
        }
    }
}
