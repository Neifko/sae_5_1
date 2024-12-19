<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Config\Database;
use Procrastinateur\Sae51\Middleware\AuthMiddleware;
use Procrastinateur\Sae51\Utils\Redirect;
use Procrastinateur\Sae51\Utils\View;

class UserManagementController
{
    public function list_all_users()
    {
        AuthMiddleware::isAdmin();
        $db = Database::getInstance();
        $users = $db->query("SELECT * FROM users");
        View::render('list_users', ['users' => $users]);
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