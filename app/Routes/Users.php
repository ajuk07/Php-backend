<?php

use App\Lib\Router;
use App\Middleware\{
    Guest,
};
use App\Controllers\{
    User,
};

$router = new Router();

$userController = new User();

$router->get('/v1/users', [$userController, 'getAllUsers']);
$router->get('/v1/users/:id', [$userController, 'getUserById']);
$router->post('/v1/users', [$userController, 'createUser']);
$router->put('/v1/users/:id', [$userController, 'updateUser']);
$router->delete('/v1/users/:id', [$userController, 'deleteUser']);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$router->dispatch($requestMethod, $requestUri);
