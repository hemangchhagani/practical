<?php
namespace App\Middleware;

use App\Controllers\AuthController;
use App\Services\AuthService;
use App\Lib\Response;

class AuthMiddleware
{

    /**
     * Extract user ID from the token if it's valid
     *
     * @return mixed User ID if token is valid, null otherwise
     */
    public static function getUserIdFromToken()
    {
        // Get Authorization header
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            Response::unauthorized('Authorization header is missing');
            exit;
        }

        // Extract Bearer token
        $authToken = str_replace('Bearer ', '', $headers['Authorization']);

        // Validate token using AuthService
        $userId = AuthService::validateToken($authToken);
        // echo "<pre>";
        // print_r($userId);
        // exit;
        // Check if userId is returned
        if (!$userId) {
            Response::unauthorized('Invalid or expired token');
            exit;
        }

        return $userId;
    }
}
