<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Utils\View;

class HexdumpController
{
    private $pythonScript;

    public function __construct()
    {
        // Chemin vers le script Python
        $this->pythonScript = __DIR__ . '/../Utils/python/hexdump_scapy.py';
    }

    public function index()
    {
        // Affiche la vue principale avec le formulaire de sélection
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
    
        $result = null;
        try {
            switch ($action) {
                case 'capture':
                    $interface = $_POST['interface'] ?? null;
                    if (!$interface) {
                        throw new \Exception('Veuillez spécifier une interface réseau.');
                    }
                    $result = $this->runPythonScript('capture', ['interface' => $interface]);
                    break;
    
                case 'analyze':
                    $data = $_POST['data'] ?? null;
                    if (!$data) {
                        throw new \Exception('Veuillez fournir des données brutes.');
                    }
                    $result = $this->runPythonScript('analyze', ['data' => $data]);
                    break;
    
                case 'sample':
                    $dst_ip = $_POST['dst_ip'] ?? null;
                    if (!$dst_ip) {
                        throw new \Exception('Veuillez fournir une adresse IP.');
                    }
                    $result = $this->runPythonScript('sample', ['dst_ip' => $dst_ip]);
                    break;
    
                case 'compare':
                    $data1 = $_POST['data1'] ?? null;
                    $data2 = $_POST['data2'] ?? null;
                    if (!$data1 || !$data2) {
                        throw new \Exception('Veuillez fournir deux ensembles de données.');
                    }
                    $result = $this->runPythonScript('compare', ['data1' => $data1, 'data2' => $data2]);
                    break;
    
                default:
                    throw new \Exception('Action inconnue.');
            }
        } catch (\Exception $e) {
            View::render('hexdump', ['error' => $e->getMessage()]);
            return;
        }
    
        // Rendu du résultat
        View::render('hexdump', [
            'result' => $result,
            'actions' => [
                'capture' => 'Capturer un paquet réseau',
                'analyze' => 'Analyser des données brutes',
                'sample' => 'Créer un exemple de paquet',
                'compare' => 'Comparer deux ensembles de données'
            ],
            'selected_action' => $action  // Ajout de l'action sélectionnée pour le formulaire
        ]);
    }
    
    private function runPythonScript(string $action, array $parameters = []): string
    {
        $command = escapeshellcmd("python3 {$this->pythonScript} {$action}");
        
        // Ajout des paramètres
        foreach ($parameters as $key => $value) {
            $command .= " --{$key} " . escapeshellarg($value);
        }
    
        // Exécution du script Python
        $output = [];
        $returnVar = null;
        exec($command, $output, $returnVar);
    
        // Gestion des erreurs Python
        if ($returnVar !== 0) {
            throw new \Exception("Erreur lors de l'exécution du script Python : " . implode("\n", $output));
        }
    
        // Retourne le résultat du script
        return implode("\n", $output);
    }
    
}
