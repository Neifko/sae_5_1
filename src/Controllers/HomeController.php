<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
use Victor\Sae51\Middleware\AuthMiddleware;

class HomeController
{
    public function index()
    {
        $db = Database::getInstance();


        $title = "Bienvenue sur la page d'accueil";
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
