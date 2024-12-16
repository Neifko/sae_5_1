<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
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


            if (strlen($username) > 3 && strlen($username) < 50) {
                if (strlen($password) > 8 && strlen($password) < 20) {
                    if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
                        $db = Database::getInstance();
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
        session_destroy();
        Redirect::withMessage('/login', 'Déconnexion réussie.');
    }


}