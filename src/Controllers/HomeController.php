<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;
use Procrastinateur\Sae51\Utils\Redirect;

class HomeController
{
    public function index()
    {
        if (!empty($_SESSION['user']['id']) && !empty($_SESSION['user']['username'])){
            Redirect::to('/dashboard');
        }

        include __DIR__ . '/../Views/home.php';
    }

    public function test()
    {
        echo "Page test";
    }

    public function protected_route()
    {
        AuthMiddleware::handle();
        echo "Page protégée.";

    }
}
