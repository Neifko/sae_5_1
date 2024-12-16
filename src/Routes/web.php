<?php

use Victor\Sae51\Controllers\HomeController;
use Victor\Sae51\Controllers\IPv4Controller;

$router = new AltoRouter();


$router->map('GET', '/', [HomeController::class, 'index'], 'home');
$router->map('GET', '/test', [HomeController::class, 'test'], 'test');

$router->map('GET', '/protected', [HomeController::class, 'protected_route'], 'protected');

// Route pour le module IPv4
$router->map('GET', '/ipv4', [IPv4Controller::class, 'ipv4_convert'], 'ipv4_home');

return $router;
