<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
use Victor\Sae51\Middleware\AuthMiddleware;
use Victor\Sae51\Utils\Redirect;

class AuthController
{
    public function register_form()
    {
        include __DIR__ . '/../Views/register.php';
    }

    public function register()
    {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        if (!empty($username) && !empty($password)) {


            $db = Database::getInstance();
            $result = $db->query("SELECT * FROM users WHERE username = ?", [$username]);

            if (count($result) == 0) {
                if (strlen($username) > 3 && strlen($username) < 50) {
                    if (strlen($password) > 8 && strlen($password) < 20) {
                        if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
                            $hashed_passwd = password_hash($password, PASSWORD_DEFAULT);
                            $db->query("INSERT INTO users (username, password) VALUES (?, ?)", [$username, $hashed_passwd]);

                            Redirect::to('/login');
                        } else {
                            Redirect::withMessage('/register', "Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule et 1 chiffre.");
                        }
                    } else {
                        Redirect::withMessage('/register', "Le mot de passe doit faire entre 8 et 20 caractères.");
                    }
                } else {
                    Redirect::withMessage('/register', "Le nom d'utilisateur doit faire entre 3 et 50 caractères.");
                }
            } else {
                Redirect::withMessage('/register', "Ce nom d'utilisateur est déjà prit.");
            }
        } else {
            Redirect::withMessage('/register', "Veuillez remplir tous les champs.");
        }


    }

    public function login_form()
    {
        include __DIR__ . '/../Views/login.php';

    }


    public function login()
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (!empty($username) && !empty($password)) {
            $db = Database::getInstance();
            $result = $db->query("SELECT * FROM users WHERE username = ?", [$username]);

            if (!empty($result) && password_verify($password, $result[0]['password'])) {
                $_SESSION['user'] = [
                    'id' => $result[0]['id'],
                    'username' => $result[0]['username']
                ];

                Redirect::to('/dashboard');
            } else {
                Redirect::withMessage('/login', 'Nom d’utilisateur ou mot de passe incorrect.', 'error');
            }
        } else {
            Redirect::withMessage('/login', 'Veuillez remplir tous les champs.', 'error');
        }
    }

    public function logout()
    {
        AuthMiddleware::handle();
        session_destroy();
        Redirect::withMessage('/login', 'Déconnexion réussie.');
    }

    public function profile_form($id)
    {
        AuthMiddleware::handle();

        $db = Database::getInstance();

        $result = $db->query("SELECT * FROM users WHERE id = ?", [$id]);

        $username = $result[0]['username'];

        if ($_SESSION['user']['id'] != $id) {
            http_response_code(403);
            die("Accès interdit");
        }

        include __DIR__ . '/../Views/profile.php';
    }

    public function profile_update($id)
    {
        AuthMiddleware::handle();

        if ($_SESSION['user']['id'] != $id) {
            http_response_code(403);
            die("Accès interdit");
        }

        $db = Database::getInstance();

        $result = $db->query("SELECT * FROM users WHERE id = ?", [$id]);

        $username = $result[0]['username'];

        if (!empty($_POST['old-password']) && !empty($_POST['new-password'])) {
            $old_password = trim($_POST['old-password']);
            $new_password = trim($_POST['new-password']);

            if (strlen($new_password) > 8 && strlen($new_password) < 20) {
                if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $new_password)) {
                    if (password_verify($old_password, $result[0]['password'])) {
                        $db->query("UPDATE users SET password = ? WHERE username = ?", [password_hash($new_password, PASSWORD_DEFAULT), $username]);
                        Redirect::withMessage('/profile/' . $id, "Mot de passe modifié.");
                    } else {
                        Redirect::withMessage('/profile/' . $id, "L'ancien mot de passe est incorrect.");
                    }
                } else {
                    Redirect::withMessage('/profile/' . $id, "Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule et 1 chiffre.");
                }
            } else {
                Redirect::withMessage('/profile/' . $id, "Le mot de passe doit faire entre 8 et 20 caractères.");
            }
        } else {
            Redirect::withMessage('/profile/' . $id, "Veuillez remplir tous les champs.");
        }
    }


}