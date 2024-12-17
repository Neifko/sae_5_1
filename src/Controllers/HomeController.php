<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
use Victor\Sae51\Middleware\AuthMiddleware;
use Victor\Sae51\Utils\Redirect;

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
