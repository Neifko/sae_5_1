<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;
use Procrastinateur\Sae51\Utils\View;

class ModuleController
{

    public function module_sousreseau()
    {
        AuthMiddleware::handle();
        View::render("module_sousreseau");
    }

}
