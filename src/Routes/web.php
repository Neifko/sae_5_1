<?php

use Victor\Sae51\Controllers\HomeController;
use Victor\Sae51\Controllers\ModuleController;


$router = new AltoRouter();


$router->map('GET', '/', [HomeController::class, 'index'], 'home');
$router->map('GET', '/test', [HomeController::class, 'test'], 'test');

$router->map('GET', '/module_sousreseau', [ModuleController::class, 'module_sousreseau'], 'module_sousreseau');

$router->map('GET', '/protected', [HomeController::class, 'protected_route'], 'protected');


return $router;
