<?php
require __DIR__ . '/vendor/autoload.php';

use App\Lib\Router;
use App\Lib\Request;
use App\Lib\Response;
use App\Middleware\CorsMiddleware;
use App\Middleware\AuthMiddleware;
use App\Controllers\ResidentsController;
use App\Controllers\AuthController;
use App\Controllers\FooditemsController;
 use App\Controllers\ResidentDietPlanController;
// use App\Controllers\CategoryController;
use App\Model\User;

// Handle CORS preflight requests
CorsMiddleware::handle();

//-------------------------------------code---------------------------------//
/**
 * getAccessToken
 *
 * @return int $userId 
 */
$userId = "";
$response = new Response();
$authController = new AuthController();
$FooditemsController = new FooditemsController();
$redidentController = new ResidentsController();
$routes = new Router();

// Load routes from a separate file
require __DIR__ . '/App/Routes/api.php';




try {
    // Get the HTTP method and URI
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // Match the route and extract parameters
    
    $route = $routes->match($httpMethod, $uri);

    if ($route) {
        if($uri != '/login' && $uri != '/register'){

            $userId = AuthMiddleware::getUserIdFromToken();
        }
        $handler = $route['handler'];
        $params = $route['params'];
        $params['created_by'] = $userId; 
        $body = Request::getBody();
        if ($httpMethod === 'POST' || $httpMethod === 'PUT') {
            $params = array_merge($params, $body);
        }
        $response->toJSON(call_user_func_array($handler, [$params]));
    } else {
        $response->toJSON(['status' => 'failed', 'msg' => 'Url Not Found' ]); 
    }

} catch(Exception $e) {
    $response->toJSON(['status' => 'failed', 'msg' => 'Url Not Found']); 
}