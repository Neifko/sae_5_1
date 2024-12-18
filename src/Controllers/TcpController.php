<?php
namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Utils\View;

class TcpController {
    public function index() {
        // Initialiser la variable pour le résultat
        $tcpResult = '';

        // Traitement du formulaire POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ip = trim($_POST['ip']);
            $port = intval($_POST['port']);

            // Validation du port
            if ($port < 1 || $port > 65535) {
                $tcpResult = "Port invalide. Veuillez entrer un numéro entre 1 et 65535.";
            } else {
                // Vérifier si l'IP est valide ou si c'est un domaine
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    // C'est une IP valide
                    $resolved_ip = $ip;
                } else {
                    // C'est un nom de domaine, essayer de le résoudre en IP
                    $resolved_ip = gethostbyname($ip);
                    if ($resolved_ip === $ip) {
                        // Si le nom de domaine ne peut pas être résolu
                        $tcpResult = "Nom de domaine invalide ou non résolu.";
                    }
                }

                // Si une IP est résolue, appeler le script Python
                if (isset($resolved_ip) && filter_var($resolved_ip, FILTER_VALIDATE_IP)) {
                    // Appel du script Python
                    $command = escapeshellcmd("python3 " . __DIR__ . "/../Utils/python/tcp_scapy.py $resolved_ip $port");
                    $tcpResult = shell_exec("$command 2>&1");
                }
            }
        }

        // Inclure la vue pour afficher le formulaire et le résultat
        View::render("tcp", ['tcpResult' => $tcpResult]);
    }
}
