<?php
namespace Victor\Sae51\Controllers;

class TcpController {
    public function index() {
        // Initialiser la variable pour le résultat
        $tcpResult = '';

        // Traitement du formulaire POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ip = trim($_POST['ip']);
            $port = intval($_POST['port']);

            // Validation de l'IP et du port
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                $tcpResult = "Adresse IP invalide.";
            } elseif ($port < 1 || $port > 65535) {
                $tcpResult = "Port invalide. Veuillez entrer un numéro entre 1 et 65535.";
            } else {
                // Appeler le script Python
                $command = escapeshellcmd("python3 ".__DIR__."/../Utils/python/tcp_scapy.py $ip $port");
                $tcpResult = shell_exec("$command 2>&1");
            }
        }

        // Inclure la vue pour afficher le formulaire et le résultat
        include __DIR__ . '/../Views/tcp.php';
    }
}
