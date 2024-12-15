<?php

namespace Victor\Sae51;

use AltoRouter;

class Router {
    public static function run() {
        // Charger le router depuis le fichier de configuration
        $router = require __DIR__ . '/Routes/web.php';

        // Faire correspondre la requête actuelle à une route
        $match = $router->match();


        if ($match) {
            [$controller_class, $method] = $match['target'];

            // Vérifier que la classe et la méthode existent
            if (class_exists($controller_class) && method_exists($controller_class, $method)) {
                // Appeler la méthode avec les paramètres de la route
                $controller = new $controller_class();
                call_user_func_array([$controller, $method], $match['params']);
            } else {
                http_response_code(500);
                echo "Erreur : Contrôleur ou méthode introuvable.";
            }
        } else {
            http_response_code(404);
            echo "Erreur 404 : Page non trouvée 1.";
        }
    }
}