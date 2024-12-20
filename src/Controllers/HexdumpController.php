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
            throw new \Exception('Impossible de résoudre le chemin du script Python.');
        }
    }

    public function index()
    {
        session_start();

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
                'analyze' => 'Analyser des données brutes',
                'sample' => 'Créer un exemple de paquet',
                'compare' => 'Comparer deux ensembles de données',
                'pcap' => 'Analyser un fichier .pcap'  // Ajout d'une option pour analyser un fichier .pcap
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
                    // Logique pour capturer des paquets
                    break;

                case 'analyze':
                    $data = $_POST['data'] ?? null;
                    if (!$data) {
                        throw new \Exception('Veuillez fournir des données brutes.');
                    }
                    $command = "python3 " . escapeshellarg($this->pythonScript) . " analyze --data " . escapeshellarg($data);
                    break;

                case 'sample':
                    $dst_ip = $_POST['dst_ip'] ?? null;
                    if (!$dst_ip) {
                        throw new \Exception('Veuillez fournir une adresse IP.');
                    }
                    $command = "python3 " . escapeshellarg($this->pythonScript) . " sample --dst_ip " . escapeshellarg($dst_ip);
                    break;

                case 'compare':
                    $data1 = $_POST['data1'] ?? null;
                    $data2 = $_POST['data2'] ?? null;
                    if (!$data1 || !$data2) {
                        throw new \Exception('Veuillez fournir deux ensembles de données.');
                    }
                    $command = "python3 " . escapeshellarg($this->pythonScript) . " compare --data1 " . escapeshellarg($data1) . " --data2 " . escapeshellarg($data2);
                    break;

                case 'pcap':  // Analyser un fichier .pcap
                    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                        throw new \Exception('Veuillez télécharger un fichier .pcap valide.');
                    }

                    $fileTmpPath = $_FILES['file']['tmp_name'];
                    $command = "python3 " . escapeshellarg($this->pythonScript) . " analyze_pcap --file " . escapeshellarg($fileTmpPath);
                    break;

                default:
                    throw new \Exception('Action inconnue.');
            }

            $command .= " 2>&1";
            $output = shell_exec($command);

            if (!$output) {
                throw new \Exception('Le script Python n’a pas retourné de résultat.');
            }

            $result = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Erreur lors de l’interprétation des résultats JSON : ' . json_last_error_msg());
            }

        } catch (\Exception $e) {
            View::render('hexdump', ['error' => $e->getMessage()]);
            return;
        }

        View::render('hexdump', [
            'result' => $result,
            'actions' => [
                'capture' => 'Capturer un paquet réseau',
                'analyze' => 'Analyser des données brutes',
                'sample' => 'Créer un exemple de paquet',
                'compare' => 'Comparer deux ensembles de données',
                'pcap' => 'Analyser un fichier .pcap'
            ],
            'selected_action' => $action
        ]);
    }
}
