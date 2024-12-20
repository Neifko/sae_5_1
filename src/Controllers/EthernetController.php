<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;
use Procrastinateur\Sae51\Utils\Redirect;
use Procrastinateur\Sae51\Utils\View;

class EthernetController
{

    function index()
    {
        AuthMiddleware::handle();


        View::render('ethernet');
    }

    function compute()
    {
        AuthMiddleware::handle();

        $errors = [];
        $data = [];

        // Validation des champs

        // Préambule (7 octets, 10101010 en hexadécimal)
        if (!isset($_POST['preambule']) || $_POST['preambule'] !== str_repeat('10101010', 7)) {
            $errors['preambule'] = "Le préambule doit être '10101010' sur 7 octets.";
        } else {
            $data['preambule'] = $_POST['preambule'];
        }

        // SFD (1 octet, 10101011 en hexadécimal)
        if (!isset($_POST['sfd']) || $_POST['sfd'] !== '10101011') {
            $errors['sfd'] = "Le SFD doit être '10101011' .";
        } else {
            $data['sfd'] = $_POST['sfd'];
        }

        $destination_mac = str_replace(":", "-", $_POST['destination_mac']);
        $source_mac = str_replace(":", "-", $_POST['source_mac']);

        // Adresse MAC de destination (6 octets)
        if (empty($destination_mac) || !preg_match('/^([A-Fa-f0-9]{2}-){5}[A-Fa-f0-9]{2}$/', $destination_mac)) {
            $errors['destination_mac'] = "Adresse MAC de destination invalide.";
        } else {
            $data['destination_mac'] = $destination_mac;
        }

        // Adresse MAC source (6 octets)
        if (empty($source_mac) || !preg_match('/^([A-Fa-f0-9]{2}-){5}[A-Fa-f0-9]{2}$/', $source_mac)) {
            $errors['source_mac'] = "Adresse MAC source invalide.";
        } else {
            $data['source_mac'] = $source_mac;
        }

        // EtherType (2 octets)
        if (!isset($_POST['ethertype']) || !preg_match('/^(0x[0-9A-Fa-f]{4})$/', $_POST['ethertype'])) {
            $errors['ethertype'] = "L'EtherType doit être un entier hexadécimal valide (par ex. 0x0800).";
        } else {
            $data['ethertype'] = $_POST['ethertype'];
        }

        // Données (46-1500 octets)
        if (!isset($_POST['data']) || strlen($_POST['data']) > 1500) {
            $errors['data'] = "Les données doivent avoir une longueur comprise entre 46 et 1500 octets.";
        } else if (strlen($_POST['data']) < 46) {
            $raw_data = $_POST['data'];
            $padding_length = 46 - strlen($raw_data);
            $raw_data .= str_repeat("0", $padding_length);
            $data['data'] = $raw_data;
        } else {
            $data['data'] = $_POST['data'];
        }

        if (isset($_POST['havepayload']) && $_POST['havepayload']){
            $data['havepayload'] = true;
        } else {
            $data['havepayload'] = false;
        }

        if (!isset($_POST['ip_ping']) || !preg_match('/^(25[0-5]|2[0-4][0-9]|1?[0-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1?[0-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1?[0-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1?[0-9]?[0-9])$/', $_POST['ip_ping'])) {
            //$errors['ip_ping'] = "Adresse IPv4 de destination invalide.";
        } else {
            $data['ip_ping'] = $_POST['ip_ping'];
        }

        if (!isset($_POST['interface']) ) {
            $errors['interface'] = "L'interface est invalide";
        } else {
            $data['interface'] = $_POST['interface'];
        }

        $data['action'] = $_POST['action'] ?? 'ping';

        // FCS (4 octets, généré ou laissé vide pour Scapy)
        $data['fcs'] = $_POST['fcs'] ?? ''; // Peut être vide, Scapy le générera automatiquement si nécessaire

        // Gestion des erreurs ou sauvegarde
        if (!empty($errors)) {
            Redirect::withMessage('/ethernet', json_encode(['status' => 'error', 'errors' => $errors]),"error");
        }

        // Enregistrement dans un fichier JSON
        $jsonFile = 'trame_data.json';
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

        // Réponse réussie
//        header('Content-Type: application/json');
        $success_msg =  json_encode(['status' => 'success', 'message' => 'Trame sauvegardée avec succès.', 'file' => $jsonFile]);

        $result = shell_exec("python3 " . __DIR__ . "/../Utils/python/ethernet_scapy.py 2>&1");

        View::render('ethernet', ['result' => $result, 'success_msg' => $success_msg]);
    }

}

?>

