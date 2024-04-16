<?php

use App\Lib\Router;
use App\Middleware\{
	Guest
};
use App\Controllers\{
	LoginController,
};

$router = new Router();

$loginController = new LoginController();

$router->get('/auth/login', [$loginController, 'index']);
$router->get('/auth/register', [$loginController, 'register']);


$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$router->dispatch($requestMethod, $requestUri);
