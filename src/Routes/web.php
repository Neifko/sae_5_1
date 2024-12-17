<?php

use Victor\Sae51\Controllers\AuthController;
use Victor\Sae51\Controllers\HomeController;

use Victor\Sae51\Controllers\IPv4Controller;
use Victor\Sae51\Controllers\IPv6Controller;

use Victor\Sae51\Controllers\ModuleController;
use Victor\Sae51\Controllers\TraductionIPV4Controller;
use Victor\Sae51\Controllers\PingController;
use Victor\Sae51\Controllers\MainController;


$router = new AltoRouter();


// Routes principales
$router->map('GET', '/', [HomeController::class, 'index'], 'home');
$router->map('GET', '/dashboard', [MainController::class, 'dashboard'], 'dashboard');
// Fin routes principales

// Routes de tests
$router->map('GET', '/test', [HomeController::class, 'test'], 'test');
$router->map('GET','/module_traduction', [TraductionIPV4Controller::class, 'module_traduction_get'], 'module_traduction');
$router->map('POST','/module_traduction', [TraductionIPV4Controller::class, 'module_traduction_post'], 'module_traduction_post');
$router->map('GET', '/module_sousreseau', [ModuleController::class, 'module_sousreseau'], 'module_sousreseau');

$router->map('GET', '/protected', [HomeController::class, 'protected_route'], 'protected');
// Fin routes de tests

// Routes auth modules
$router->map('GET', '/register', [AuthController::class, 'register_form'], 'register');
$router->map('POST', '/register', [AuthController::class, 'register']);
$router->map('GET', '/login', [AuthController::class, 'login_form'], 'login');
$router->map('POST', '/login', [AuthController::class, 'login']);
$router->map('GET', '/logout', [AuthController::class, 'logout'], 'logout');
$router->map('GET', '/profile/[i:id]', [AuthController::class, 'profile_form'], 'profile');
$router->map('POST', '/profile/[i:id]', [AuthController::class, 'profile_update']);
// Fin routes auth


// Route pour le module IPv4
$router->map('GET', '/ipv4', [IPv4Controller::class, 'ipv4_convert'], 'ipv4_home');


$router->map('GET', '/ipv6', [IPv6Controller::class, 'ipv6_convert'], 'ipv6_home');

// Route pour les modules Scapy
$router->map('GET|POST', '/ping', [PingController::class, 'index']);


return $router;
