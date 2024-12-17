<?php

use Victor\Sae51\Controllers\AuthController;
use Victor\Sae51\Controllers\HomeController;
use Victor\Sae51\Controllers\ModuleController;
use Victor\Sae51\Controllers\IPv4Controller;
use Victor\Sae51\Controllers\PingController;
use Victor\Sae51\Controllers\MainController;

$router = new AltoRouter();


// Routes principales
$router->map('GET', '/', [HomeController::class, 'index'], 'home');
$router->map('GET', '/dashboard', [MainController::class, 'dashboard'], 'dashboard');
// Fin routes principales

// Routes de tests
$router->map('GET', '/test', [HomeController::class, 'test'], 'test');
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





// Route pour les modules Scapy
$router->map('GET|POST', '/ping', [PingController::class, 'index']);


return $router;
