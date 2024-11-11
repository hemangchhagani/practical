<?php

namespace App\Middleware;

class RoleMiddleware
{
    public static function handle($requiredRole)
    {
        $user = AuthMiddleware::handle(); // First, authenticate the user
        if ($user->role !== $requiredRole) {
            http_response_code(403);
            echo json_encode(['message' => 'Forbidden, insufficient permissions']);
            exit;
        }
    }
}
