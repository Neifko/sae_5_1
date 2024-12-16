<?php

use Victor\Sae51\Controllers\AuthController;
use Victor\Sae51\Controllers\HomeController;
use Victor\Sae51\Controllers\IPv4Controller;
use Victor\Sae51\Controllers\ModuleController;


$router = new AltoRouter();


$router->map('GET', '/', [HomeController::class, 'index'], 'home');
$router->map('GET', '/test', [HomeController::class, 'test'], 'test');

$router->map('POST', '/module_sousreseau', [HomeController::class, 'module_sousreseau'], 'module_sousreseau');

$router->map('GET', '/test', [ModuleController::class, 'test'], 'test');


$router->map('GET', '/register', [AuthController::class, 'register_form'], 'register');
$router->map('POST', '/register', [AuthController::class, 'register']);

$router->map('GET', '/login', [AuthController::class, 'login_form'], 'login');
$router->map('POST', '/login', [AuthController::class, 'login']);
$router->map('GET', '/logout', [AuthController::class, 'logout'], 'logout');

// Route pour le module IPv4
$router->map('GET', '/ipv4', [IPv4Controller::class, 'ipv4_convert'], 'ipv4_home');

return $router;
