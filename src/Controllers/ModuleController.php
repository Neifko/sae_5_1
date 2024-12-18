<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;

class ModuleController
{

    public function module_sousreseau()
    {
        AuthMiddleware::handle();
        include __DIR__ . '/../Views/module_sousreseau.php';
    }

}
