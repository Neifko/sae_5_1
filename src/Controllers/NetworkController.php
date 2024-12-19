<?php

namespace Procrastinateur\Sae51\Controllers;

class NetworkController {
    public function index() {

        // Chemin vers le fichier JSON
        $jsonPath = __DIR__ . '/../Views/data/network_info.json';

        // Charger les données JSON
        if (file_exists($jsonPath)) {
            $networkData = json_decode(file_get_contents($jsonPath), true);
        } else {
            $networkData = [];
        }

        // Charger la vue
        require __DIR__ . '/../Views/network.php';
    }

    public function update() {
        // Exécuter le script Python
        //$network_request = shell_exec("python3 /var/www/sae_5_1/src/Utils/python/network_scapy.py");
        $network_request = shell_exec("python3 ".__DIR__."/../Utils/python/network_scapy.py");
        // Rediriger vers la vue avec un message
        header('Location: /network');
        exit();
    }
}
