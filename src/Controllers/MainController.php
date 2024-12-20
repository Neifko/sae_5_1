<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;
use Procrastinateur\Sae51\Utils\View;

class MainController
{

    public function dashboard(){
        AuthMiddleware::handle();

        $user_id = $_SESSION['user']['id'];
        $username = $_SESSION['user']['username'];

        View::render("dashboard", ['user_id' => $user_id, 'username' => $username]);
    }

    public function scapy_summary()
    {
        AuthMiddleware::handle();
        View::render("scapy_summary");
    }
}