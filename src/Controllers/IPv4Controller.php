<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;
use Procrastinateur\Sae51\Utils\View;

class IPv4Controller
{
    public function ipv4_convert()
    {
        # Validation côté serveur de l'adresse IPv4 peut etre à faire 
        AuthMiddleware::handle();
        View::render('ipv4');
    }
}