<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;

class IPv4Controller
{
    public function ipv4_convert()
    {
        # Validation côté serveur de l'adresse IPv4 peut etre à faire 
        AuthMiddleware::handle();
        include __DIR__ . '/../Views/ipv4.php';
    }
}