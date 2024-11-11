<?php
// Autoload dependencies
require_once __DIR__ . '/vendor/autoload.php';

use App\Lib\Router;
use App\Lib\Request;
use App\Lib\Response;
use App\Middleware\CorsMiddleware;
use App\Middleware\AuthMiddleware;

// Initialize the application router
$app = new Router();

// Middleware to handle CORS
CorsMiddleware::handle();

// Load routes from a separate file
require __DIR__ . '/App/Routes/api.php';

// Handle incoming requests
try {
    // Get the HTTP method and URI
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // Match the route and extract parameters
    $route = $app->match($httpMethod, $uri);
    
    if ($route) {
        // Extract handler and parameters for the matched route
        $handler = $route['handler'];
        $params = $route['params'];

        // Process authorization, skipping login and register
        $userId = null;
        if ($uri !== '/login' && $uri !== '/register') {
            
            $userId = AuthMiddleware::getUserIdFromToken();
            // Check if user ID is valid
            if (!$userId) {
                return (new Response())->toJSON(['status' => 'failed', 'msg' => 'Unauthorized'], 401);
            }
        }
        
        // Merge request body into parameters for POST or PUT requests
        $body = Request::getBody();

        if ($httpMethod === 'POST' || $httpMethod === 'PUT') {
            $params = array_merge($params, $body);
        }
        $params['created_by'] = $userId;  // Include the user ID for created_by if applicable
        
        // Invoke the route handler with the parameters
        $response = new Response();
        $response->toJSON(call_user_func_array($handler, [$params]));
    } else {
        // Return a 404 response if no route matched
        (new Response())->toJSON(['status' => 'failed', 'msg' => 'Url Not Found'], 404);
    }

} catch (Exception $e) {
    // Catch any unexpected errors
    (new Response())->toJSON(['status' => 'failed', 'msg' => $e->getMessage()], 500);
}
