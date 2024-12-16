<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
use Victor\Sae51\Middleware\AuthMiddleware;

class IPv4Controller
{

    public function ipv4_convert()
    {
        # Validation côté serveur de l'adresse IPv4 peut etre à faire 
        #AuthMiddleware::handle();
        include __DIR__ . '/../Views/ipv4.php';
    }

    
}