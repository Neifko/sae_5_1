<?php
namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;

class PingController {
    public function index() {
        AuthMiddleware::handle();
        // Traitement du formulaire
        $pingResult = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $host = trim($_POST['host']);

            // Validation de l'adresse IP ou de l'URL
            if (filter_var($host, FILTER_VALIDATE_IP)) {
                $ip = $host;
            } elseif (filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
                $ip = gethostbyname($host);
                if ($ip === $host) {
                    $pingResult = "Impossible de résoudre l'URL : $host";
                }
            } else {
                $pingResult = "Entrée invalide. Veuillez entrer une adresse IP ou une URL valide.";
            }

            // Si l'IP est valide, exécuter le script Python
            if ($pingResult === '') {
                $pingResult = shell_exec("python3 ".__DIR__."/../Utils/python/ping_scapy.py $ip 2>&1");
            }
        }

        // Inclure la vue pour afficher le résultat
        include __DIR__ . '/../Views/ping.php';
    }
}
