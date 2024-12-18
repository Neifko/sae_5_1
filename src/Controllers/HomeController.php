<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;
use Procrastinateur\Sae51\Utils\Redirect;
use Procrastinateur\Sae51\Utils\View;

class HomeController
{
    public function index()
    {
        if (!empty($_SESSION['user']['id']) && !empty($_SESSION['user']['username'])){
            Redirect::to('/dashboard');
        }

//        include __DIR__ . '/../Views/home.php';
        View::render('home');
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
