<?php

use Procrastinateur\Sae51\Controllers\AuthController;
use Procrastinateur\Sae51\Controllers\HomeController;
use Procrastinateur\Sae51\Controllers\IPv4Controller;
use Procrastinateur\Sae51\Controllers\IPv6Controller;
use Procrastinateur\Sae51\Controllers\ModuleController;
use Procrastinateur\Sae51\Controllers\TraductionIPV4Controller;
use Procrastinateur\Sae51\Controllers\PingController;
use Procrastinateur\Sae51\Controllers\TcpController;
use Procrastinateur\Sae51\Controllers\NetworkController;
use Procrastinateur\Sae51\Controllers\EthernetController;
use Procrastinateur\Sae51\Controllers\HexdumpController;
use Procrastinateur\Sae51\Controllers\MainController;
use Procrastinateur\Sae51\Controllers\UserManagementController;

$router = new AltoRouter();


// Routes principales
$router->map('GET', '/', [HomeController::class, 'index'], 'home');
$router->map('GET', '/dashboard', [MainController::class, 'dashboard'], 'dashboard');
// Fin routes principales

// Routes de tests
$router->map('GET', '/test', [HomeController::class, 'test'], 'test');
$router->map('GET', '/protected', [HomeController::class, 'protected_route'], 'protected');
// Fin routes de tests

// Route module_sousreseau
$router->map('GET','/module_traduction', [TraductionIPV4Controller::class, 'module_traduction_get'], 'module_traduction');
$router->map('POST','/module_traduction', [TraductionIPV4Controller::class, 'module_traduction_post'], 'module_traduction_post');
$router->map('GET', '/module_sousreseau', [ModuleController::class, 'module_sousreseau'], 'module_sousreseau');

// Routes auth modules
$router->map('GET', '/register', [AuthController::class, 'register_form'], 'register');
$router->map('POST', '/register', [AuthController::class, 'register']);
$router->map('GET', '/login', [AuthController::class, 'login_form'], 'login');
$router->map('POST', '/login', [AuthController::class, 'login']);
$router->map('GET', '/logout', [AuthController::class, 'logout'], 'logout');
$router->map('GET', '/profile/[i:id]', [AuthController::class, 'profile_form'], 'profile');
$router->map('POST', '/profile/[i:id]', [AuthController::class, 'profile_update']);
// Fin routes auth

// Debut routes UserManagement
$router->map('GET', '/list-users', [UserManagementController::class, 'list_all_users'], 'list_users');
$router->map('GET', '/delete-user/[i:id]', [UserManagementController::class, 'delete_user'], 'delete_user');
// Fin routes UserManagement

// Route pour le module IPv4
$router->map('GET', '/ipv4', [IPv4Controller::class, 'ipv4_convert'], 'ipv4_home');
$router->map('GET', '/ipv6', [IPv6Controller::class, 'ipv6_convert'], 'ipv6_home');

// Route pour les modules Scapy
$router->map('GET', '/scapy', [MainController::class, 'scapy_summary']);
$router->map('GET|POST', '/ping', [PingController::class, 'index']);
$router->map('GET|POST', '/tcp', [TcpController::class, 'index']);

$router->map('GET', '/network', [NetworkController::class, 'index']);
$router->map('GET', '/network/update', [NetworkController::class, 'update']);

$router->map('GET', '/ethernet', [EthernetController::class, 'index']);
$router->map('POST', '/ethernet', [EthernetController::class, 'compute']);
$router->map('GET', '/hexdump', [HexdumpController::class, 'index']);
$router->map('POST', '/hexdump/process', [HexdumpController::class, 'process']);

// Fin des routes du modules Scapy


return $router;
