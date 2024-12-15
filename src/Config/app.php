<?php

use Dotenv\Dotenv;

// Charger les variables .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

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