<?php

namespace Victor\Sae51\Config;

use PDO;
use PDOException;

class Database
{
    private PDO $pdo;
    private static ?Database $instance = null;

    private function __construct()
    {

        try {
            $this->pdo = new PDO(
                'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }

    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function query(string $sql, array $params = [])
    {
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
            if (stripos($sql, "SELECT") === 0) {
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            }
            return ['row_count' => $statement->rowCount()];
        } catch (PDOException $e) {
            die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
    }
}