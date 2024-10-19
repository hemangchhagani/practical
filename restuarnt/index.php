<?php


header("Access-Control-Allow-Origin: *");  // Allow all origins
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight (OPTIONS) requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); // Exit here so further code does not run
}


// Autoload dependencies
require_once __DIR__ . '../vendor/autoload.php';

// Load the bootstrap file to initialize the app
require_once __DIR__ . '../bootstrap/app.php';

use App\Middleware\MiddlewareHandler;
use App\Middleware\AuthMiddleware;

header('Content-Type: application/json');

// Include routes
$dispatcher = require __DIR__ . '/src/routes/api.php'; // Load the dispatcher from api.php

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$request = $_REQUEST;

// Handle query string parameters
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Fetch route information
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(['message' => 'Route not found']);
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        // Initialize middleware handler
        $middlewareHandler = new MiddlewareHandler();

        // Apply middleware only for specific routes
        if ($uri === '/users') {
            // Add authentication middleware for protected routes
            $middlewareHandler->add(function () {
                AuthMiddleware::handle();
            });
        }

        // Execute middleware, then controller
        $middlewareHandler->handle($request, function() use ($handler, $vars) {
            call_user_func_array($handler, $vars);
        });

        break;
}
