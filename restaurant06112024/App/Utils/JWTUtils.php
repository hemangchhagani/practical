<?php
namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JWTUtils
{
    // Method to generate a JWT token
    public static function encode($data, $key)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // JWT valid for 1 hour from the issued time
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data // Store any data you want in the token
        ];

        // Generate JWT token
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    // Method to decode the JWT token
    public static function decode($jwt, $key)
    {

// Your secret key to decode the token (must be the same as the key used for encoding)

        
// The JWT token you want to decode (Replace this with the token you want to decode)
$jwtToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzExODY5MDgsImV4cCI6MTczMTE5MDUwOCwiZGF0YSI6eyJpYXQiOjE3MzExODY5MDgsImV4cCI6MTczMTE5MDUwOCwiZGF0YSI6eyJpZCI6IjEiLCJlbWFpbCI6ImpvaG5AZXhhbXBsZS5jb20ifX19.TKgfAJlpKJLScozQrrXetJTZ4L3wZiR5xJrn4mV9Rhw';

try {
    // Decode the JWT
    $decoded = JWT::decode($jwtToken, new Key($key, 'HS256'));

    // Convert the decoded object to an associative array for better readability
    $decodedArray = (array) $decoded;
    
    // Print the decoded data
    echo "Decoded JWT Token:\n";
    print_r($decodedArray);
    
} catch (Exception $e) {
    echo 'Error decoding JWT: ' . $e->getMessage();
}
    }
}
