<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;
use Procrastinateur\Sae51\Utils\View;

class IPv6Controller
{
    public function ipv6_convert()
    {
        AuthMiddleware::handle();
        View::render('ipv6');
    }
}