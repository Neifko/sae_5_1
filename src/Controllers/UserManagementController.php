<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
use Victor\Sae51\Middleware\AuthMiddleware;
use Victor\Sae51\Utils\Redirect;

class UserManagementController
{
    public function list_all_users()
    {
        AuthMiddleware::isAdmin();
        $db = Database::getInstance();
        $users = $db->query("SELECT * FROM users");
        include __DIR__ . "/../Views/list_users.php";
    }

    public function delete_user($id)
    {
        AuthMiddleware::isAdmin();

        $db = Database::getInstance();

        $result = $db->query("SELECT * FROM users WHERE id = ?", [$id]);
        if ($result[0]['admin'] !== 1) {
            $db->query("DELETE FROM users WHERE id = ?", [$id]);

            Redirect::withMessage('/list-users', "L'utilisateur a été supprimé.", 'success');
        } else {
            Redirect::withMessage('/list-users', "L'utilisateur est administrateur. Il ne peut pas être supprimé.", "error");
        }


    }
}