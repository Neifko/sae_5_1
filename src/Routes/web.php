<?php

use Victor\Sae51\Controllers\AuthController;
use Victor\Sae51\Controllers\HomeController;
use Victor\Sae51\Controllers\ModuleController;

use Victor\Sae51\Controllers\IPv4Controller;

$router = new AltoRouter();


$router->map('GET', '/', [HomeController::class, 'index'], 'home');
$router->map('GET', '/test', [HomeController::class, 'test'], 'test');

// Route module_sousreseau
$router->map('GET', '/module_sousreseau', [ModuleController::class, 'module_sousreseau'], 'module_sousreseau');

$router->map('GET', '/protected', [HomeController::class, 'protected_route'], 'protected');


$router->map('GET', '/register', [AuthController::class, 'register_form'], 'register');
$router->map('POST', '/register', [AuthController::class, 'register']);

$router->map('GET', '/login', [AuthController::class, 'login_form'], 'login');
$router->map('POST', '/login', [AuthController::class, 'login']);
$router->map('GET', '/logout', [AuthController::class, 'logout'], 'logout');

// Route pour le module IPv4
$router->map('GET', '/ipv4', [IPv4Controller::class, 'ipv4_convert'], 'ipv4_home');

return $router;
