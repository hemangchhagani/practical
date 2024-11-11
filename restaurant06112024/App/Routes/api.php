<?php

use App\Controllers\AuthController;
use App\Controllers\FooditemsController;
use App\Controllers\ResidentsController;
use App\Lib\Router;
$authController = new AuthController();
$foodController = new FooditemsController();
$residentController = new ResidentsController();


$routes = new Router();

$routes->addRoute('POST', '/login', [$authController, 'login']);
$routes->addRoute('POST', '/register', [$authController, 'register']);

// Food API routes

// Define routes for FoodItems
$routes->post('/food-items', [$foodController, 'create']);
$routes->get('/food-items', [$foodController, 'readAll']);
$routes->get('/food-items/{id}', [$foodController, 'read']);
$routes->put('/food-items/{id}', [$foodController, 'update']);
$routes->delete('/food-items/{id}', [$foodController, 'delete']);


// Resident API routes

// Define routes for FoodItems
$routes->post('/residents', [$residentController, 'create']);
$routes->get('/residents', [$residentController, 'readAll']);
$routes->get('/residents/{id}', [$residentController, 'read']);
$routes->put('/residents/{id}', [$residentController, 'update']);
$routes->delete('/residents/{id}', [$residentController, 'delete']);


return $routes;