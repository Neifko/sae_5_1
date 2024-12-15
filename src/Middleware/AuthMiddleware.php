<?php

namespace Victor\Sae51\Middleware;

class AuthMiddleware
{
    public static function handle()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            die("Accès interdit : veuillez vous connecter.");
        }
    }
}
