<?php
use FastRoute\RouteCollector;

use App\Controllers\AuthController;


$authController = new AuthController();


// Define authentication routes
$app->post('/login', [$authController, 'login']);
$app->post('/register', [$authController, 'register']);
