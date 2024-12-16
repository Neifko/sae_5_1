<?php

use Victor\Sae51\Controllers\AuthController;
use Victor\Sae51\Controllers\HomeController;
use Victor\Sae51\Controllers\TraductionIPV4Controller;
use Victor\Sae51\Controllers\ModuleController;

use Victor\Sae51\Controllers\IPv4Controller;

$router = new AltoRouter();


$router->map('GET', '/', [HomeController::class, 'index'], 'home');
$router->map('GET', '/test', [HomeController::class, 'test'], 'test');
$router->map('GET','/module_traduction', [TraductionIPV4Controller::class, 'module_traduction_get'], 'module_traduction');
$router->map('POST','/module_traduction', [TraductionIPV4Controller::class, 'module_traduction_post'], 'module_traduction_post');

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
