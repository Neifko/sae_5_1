<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
use Victor\Sae51\Middleware\AuthMiddleware;

class IPv6Controller
{
    public function ipv6_convert()
    {
        #AuthMiddleware::handle();
        include __DIR__ . '/../Views/ipv6.php';
    }
}