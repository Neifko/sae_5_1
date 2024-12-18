<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Middleware\AuthMiddleware;

class MainController
{

    public function dashboard(){
        AuthMiddleware::handle();

        $user_id = $_SESSION['user']['id'];

        include __DIR__ . "/../Views/dashboard.php";
    }

    public function scapy_summary()
    {
        AuthMiddleware::handle();
        include __DIR__ . "/../Views/scapy_summary.php";
    }
}