<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
use Victor\Sae51\Middleware\AuthMiddleware;

class UserManagementController
{
    public function list_all_users()
    {
        AuthMiddleware::isAdmin();
        $db = Database::getInstance();
        $users = $db->query("SELECT * FROM users");
        include __DIR__ . "/../View/list_users.php";
    }

    public function delete_user($id)
    {
        AuthMiddleware::isAdmin();

    }
}