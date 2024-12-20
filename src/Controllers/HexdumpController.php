<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Utils\View;

class HexdumpController
{
    private $pythonScript;

    public function __construct()
    {
        $this->pythonScript = realpath(__DIR__ . '/../Utils/python/hexdump_scapy.py');

        if (!$this->pythonScript) {
            $error = "Script non accessible.";
            View::render("hexdump", ['error' => $error]);
            throw new \Exception('VScript non accessible.');
        }
    }

    public function index()
    {

        $error = $_SESSION['error'] ?? null;
        $result = $_SESSION['result'] ?? null;
        $selected_action = $_SESSION['selected_action'] ?? null;

        unset($_SESSION['error'], $_SESSION['result'], $_SESSION['selected_action']);

        View::render('hexdump', [
            'error' => $error,
            'result' => $result,
            'selected_action' => $selected_action,
            'actions' => [
                'capture' => 'Capturer un paquet réseau',
                'sample' => 'Créer un exemple de paquet'
            ]
        ]);
    }

    public function process()
    {
        $action = $_POST['action'] ?? null;
        
        if (!$action) {
            View::render('hexdump', ['error' => 'Aucune action sélectionnée.']);
            return;
        }
    
        try {
            switch ($action) {
                case 'capture':
                    $interface = $_POST['interface'] ?? null;
                    if (!$interface) {
                        $error = "Veuillez spécifier une interface réseau.";
                        View::render("hexdump", ['error' => $error]);
                        throw new \Exception('Veuillez spécifier une interface réseau.');
                    }
                    $command = "python3 " . escapeshellarg($this->pythonScript) . " capture --interface " . escapeshellarg($interface);
                    break;
    
                case 'sample':
                    $dst_ip = $_POST['dst_ip'] ?? null;
                    if (!$dst_ip) {
                        $error = "Veuillez fournir une adresse IP.";
                        View::render("hexdump", ['error' => $error]);
                        throw new \Exception('Veuillez fournir une adresse IP.');
                    }
                    $command = "python3 " . escapeshellarg($this->pythonScript) . " sample --dst_ip " . escapeshellarg($dst_ip);
                    break;
    
                default:
                    $error = "Action inconnue.";
                    View::render("hexdump", ['error' => $error]);
                    throw new \Exception('Action inconnue.');
            }
    
            $command .= " 2>&1";
            $output = shell_exec($command);
    
            if (!$output) {
                $error = "Pas de résultat obtenu.";
                View::render("hexdump", ['error' => $error]);
                throw new \Exception('Le script Python n’a pas retourné de résultat.');
            }
    
            $result = json_decode($output, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                $error = "Erreur lors de l'interprétation des résultats JSON : " . json_last_error_msg();
                View::render("hexdump", ['error' => $error]);
                throw new \Exception('Erreur lors de l’interprétation des résultats JSON : ' . json_last_error_msg());
            }
    
        } catch (\Exception $e) {
            // View::render('hexdump', ['error' => $e->getMessage()]);
            return;
        }

        View::render('hexdump', [
            'result' => $result,
            'actions' => [
                'capture' => 'Capturer un paquet réseau',
                'sample' => 'Créer un exemple de paquet',
            ],
            'selected_action' => $action
        ]);
    }
    
    
}
