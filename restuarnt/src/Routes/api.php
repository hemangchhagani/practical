<?php

use FastRoute\RouteCollector;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\FooditemsController;
use App\Controllers\ResidentsController;

return FastRoute\simpleDispatcher(function (RouteCollector $r) use ($db) {
    // Auth routes (public)
    $r->addRoute('POST', '/register', [new AuthController($db), 'register']);
    $r->addRoute('POST', '/login', [new AuthController($db), 'login']);

    // Protected routes (requiring authentication middleware)
    $r->addRoute('GET', '/users', [new UserController($db), 'getUsers']);

    $r->addRoute('POST', '/food-items', [new FooditemsController($db), 'create']); // Create food item
    $r->addRoute('GET', '/food-items', [new FooditemsController($db), 'readAll']); // Read all food items
    $r->addRoute('GET', '/food-items/{id:\d+}', [new FooditemsController($db), 'read']); // Read single food item
    $r->addRoute('PUT', '/food-items/{id:\d+}', [new FooditemsController($db), 'update']); // Update food item
    $r->addRoute('DELETE', '/food-items/{id:\d+}', [new FooditemsController($db), 'delete']); // Delete food item

    $r->addRoute('POST', '/residents', [new ResidentsController($db), 'create']); // Create resident
    $r->addRoute('GET', '/residents', [new ResidentsController($db), 'readAll']); // Read all residents
    $r->addRoute('GET', '/residents/{id:\d+}', [new ResidentsController($db), 'read']); // Read single resident
    $r->addRoute('PUT', '/residents/{id:\d+}', [new ResidentsController($db), 'update']); // Update resident
    $r->addRoute('DELETE', '/residents/{id:\d+}', [new ResidentsController($db), 'delete']); // Delete resident

});
