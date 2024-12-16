<?php

use Dotenv\Dotenv;
use Victor\Sae51\Config\Database;

// Charger les variables .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

session_start();

// Creation de l'utilisateur admin
$db = Database::getInstance();

$result = $db->query("SELECT username FROM users WHERE username = 'admin'");
if (count($result) === 0){
    $db->query("INSERT INTO users (username, password, admin) VALUES (?, ?, ?)", [$_ENV['ADMIN_PWD'], password_hash($_ENV['ADMIN_UNAME'],PASSWORD_DEFAULT), true]);
}

// Configuration globale
return [
    'name' => $_ENV['APP_NAME'],
    'env' => $_ENV['APP_ENV'],
    'debug' => $_ENV['APP_DEBUG'] === 'true',
    'database' => [
        'host' => $_ENV['DB_HOST'],
        'name' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'pass' => $_ENV['DB_PASS'],
    ],
];