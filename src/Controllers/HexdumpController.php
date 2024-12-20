<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Utils\View;

class HexdumpController
{
    private $pythonScript;
    private $jsonDir;

    public function __construct()
    {
        // Résolution des chemins
        $this->pythonScript = realpath(__DIR__ . '/../Utils/python/hexdump_scapy.py');
        $this->jsonDir = realpath(__DIR__ . '/../Views/data/hexdumpFiles');

        if (!$this->pythonScript || !$this->jsonDir) {
            throw new \Exception('Impossible de résoudre les chemins pour le script ou le répertoire JSON.');
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
                'compare' => 'Comparer deux ensembles de données'
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
                        throw new \Exception('Veuillez spécifier une interface réseau.');
                    }
                    $command = "python3 " . escapeshellarg($this->pythonScript) . " capture --interface " . escapeshellarg($interface);
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

                default:
                    throw new \Exception('Action inconnue.');
            }

            $command .= " 2>&1";
            $output = shell_exec($command);
            var_dump($command);
            var_dump($output);

            $resultFile = $this->jsonDir . '/' . $this->getResultFileName($action);
            if (!file_exists($resultFile)) {
                throw new \Exception('Le fichier de résultat attendu est introuvable.');
            }

            $result = json_decode(file_get_contents($resultFile), true);

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
                'compare' => 'Comparer deux ensembles de données'
            ],
            'selected_action' => $action
        ]);
    }

    private function getResultFileName(string $action): string
    {
        $actionMap = [
            'capture' => 'capture.json',
            'analyze' => 'simple_analyze.json',
            'sample' => 'packet.json',
            'compare' => 'double_analyze.json'
        ];

        return $actionMap[$action] ?? '';
    }
}
