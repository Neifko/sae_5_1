<?php 

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Utils\View;

class HexdumpController
{
    private $pythonScript;
    private $jsonDir;

    public function __construct()
    {
        $this->pythonScript = __DIR__ . '/../Utils/python/hexdump_scapy.py';
        $this->jsonDir = __DIR__ . '/../Views/data/hexdumpFiles';
    }

    public function index()
    {
        View::render('hexdump', [
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
            $output = null;

            // Construction de la commande selon l'action
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
                    //$command = "python3 " . escapeshellarg($this->pythonScript) . " analyze --data " . escapeshellarg($data);
                    $command = escapeshellcmd("python3 " . $this->pythonScript . " analyze --data " . $data);
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

            // Exécution de la commande

            echo $command;
            echo "<br>";
            var_dump($command);
            echo "<br>";

            $output = shell_exec($command);
            echo "<br>";

            var_dump($output);

            // Lecture du fichier JSON
            $result = json_decode(file_get_contents($resultFile), true);

        } catch (\Exception $e) {
            View::render('hexdump', ['error' => $e->getMessage()]);
            return;
        }

        // Rendu de la vue avec les résultats
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
