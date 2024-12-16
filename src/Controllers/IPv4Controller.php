<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
use Victor\Sae51\Middleware\AuthMiddleware;

class IPv4Controller
{
    public function ipv4_index()
    {
        #AuthMiddleware::handle();
        
        $title = "Module IPv4";        
        include __DIR__ . '/../Views/ipv4.php';
    }

    public function ipv4_convert()
    {
        #AuthMiddleware::handle();
        # TODO
    }

    
}