<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
use Victor\Sae51\Middleware\AuthMiddleware;

class ModuleController
{

    public function module_sousreseau()
    {
        AuthMiddleware::handle();
        include __DIR__ . '/../Views/module_sousreseau.php';
    }

}
