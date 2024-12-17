<?php

namespace Victor\Sae51\Middleware;

use Victor\Sae51\Config\Database;

class AuthMiddleware
{
    public static function handle()
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            die("Accès interdit : veuillez vous connecter.");
        }
    }

    public static final function isAdmin()
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id']) && !empty($_SESSION['user']['username'])) {
            $db = Database::getInstance();
            $result = $db->query("SELECT * FROM `users` WHERE `id` = ?", [$_SESSION['user']['id']]);

            if (count($result) === 1 && !$result[0]['admin']) {
                http_response_code(403);
                die("Accès interdit : vous n'êtes pas administrateur.");
            }
        }
    }
}
