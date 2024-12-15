<?php

use Victor\Sae51\Controllers\HomeController;

$router = new AltoRouter();


$router->map('GET', '/', [HomeController::class, 'index'], 'home');
$router->map('GET', '/test', [HomeController::class, 'test'], 'test');

$router->map('GET', '/protected', [HomeController::class, 'protected_route'], 'protected');


return $router;
