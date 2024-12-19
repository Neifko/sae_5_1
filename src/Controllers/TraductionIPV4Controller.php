<?php

namespace Procrastinateur\Sae51\Controllers;

use Procrastinateur\Sae51\Middleware\AuthMiddleware;
use Procrastinateur\Sae51\Utils\View;

class TraductionIPV4Controller
{

    private function traduction_ipv4_view() : void{
        AuthMiddleware::handle();
        $title = "Module de Traduction IPV4";
        $resultat = '';
        $formats_disponibles = [];
        $adresse_detectee = '';
        $step = 1; // étape par défaut : détecter le format d'entrée
        $erreur = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $address = $_POST['address'] ?? '';
            $step = intval($_POST['step'] ?? 1); // détecter l'étape actuelle (1 ou 2)

            $controller = new TraductionIPV4Controller();

            if ($step === 1) { // détection format d'entrée
                $adresse_detectee = $controller->detecter_format($address);
                if ($adresse_detectee !== "Adresse invalide") {
                    if ($adresse_detectee !== 'Format Non Unique') {
                        $formats_disponibles = $controller->obtenir_formats_sortie($adresse_detectee);
                    } else { // si par exemple 12.12.12.12 -> hex et dec
                        $formats_disponibles = $controller->obtenir_formats_sortie($adresse_detectee);
                    }
                    $step = 2;
                } else {
                    $erreur = "L'adresse saisie est invalide. Veuillez entrer une adresse IPv4 correcte.";
                }
            } elseif ($step === 2) { // traduire selon le choix demandé
                $choix_format = $_POST['choix_format'] ?? '';
                $adresse_detectee = $_POST['adresse_detectee'];
                $resultat = $controller->script_traduction($address, $choix_format);
                $step = 3;
            }
        }

        $address = $address ?? 'aa';
        View::render('module_traduction_ipv4',
            [
                'adresse_detectee' => $adresse_detectee,
                'resultat' => $resultat,
                'formats_disponibles' => $formats_disponibles,
                'step' => $step,
                'title' => $title,
                'address' => $address,
                'erreur' => $erreur
            ]);
    }

    public function module_traduction_get()
    {
        $this->traduction_ipv4_view();
    }
    public function module_traduction_post()
    {
        $this->traduction_ipv4_view();
    }

    public function script_traduction($adresse, $choix): string
    {
        $adresse_separee = explode('.', $adresse);

        if ($choix === 'binary'){
            if ($this->is_decimal($adresse_separee[0])) {
                return implode('.', array_map([$this, 'dec_to_bin'], $adresse_separee));
            }
            elseif ($this->is_hexadecimal($adresse_separee[0])) {
                return implode('.', array_map([$this, 'hex_to_bin'], $adresse_separee));
            }
        }
        elseif ($choix === 'hexadecimal'){
            if ($this->is_decimal($adresse_separee[0])) {
                return implode('.', array_map([$this, 'dec_to_hex'], $adresse_separee));
            }
            elseif ($this->is_binary($adresse_separee[0])) {
                return implode('.', array_map([$this, 'bin_to_hex'], $adresse_separee));
            }
        }
        elseif ($choix === 'decimal'){
            if ($this->is_binary($adresse_separee[0])) {
                return implode('.', array_map([$this, 'bin_to_dec'], $adresse_separee));
            }
            elseif ($this->is_hexadecimal($adresse_separee[0])) {
                return implode('.', array_map([$this, 'hex_to_dec'], $adresse_separee));
            }
        }
        return "Conversion impossible avec le format choisi.";
    }

    public function detecter_format($adresse): string
    {
        $adresse_split = explode('.', $adresse);

        if ($this->is_validIPv4($adresse_split)) {
            $is_dec = array_reduce($adresse_split, function ($carry, $octet) {
                return $carry && $this->is_decimal($octet);
            }, true);
            $is_bin = array_reduce($adresse_split, function ($carry, $octet) {
                return $carry && $this->is_binary($octet);
            }, true);
            $is_hex = array_reduce($adresse_split, function ($carry, $octet) {
                return $carry && $this->is_hexadecimal($octet);
            }, true);

            if ($is_dec && $is_hex) return 'Format Non Unique';
            if ($is_dec) return 'decimal';
            if ($is_bin) return 'binary';
            if ($is_hex) return 'hexadecimal';
        }
        return "Adresse invalide";
    }
    public function obtenir_formats_sortie($format_detecte): array
    {
        $formats = [
            'decimal' => 'Décimal',
            'binary' => 'Binaire',
            'hexadecimal' => 'Hexadécimal',
        ];
        if ($format_detecte !== 'Format Non Unique') {
            unset($formats[$format_detecte]); // exclure le format détecté d'origine si 1 seul format détecté
        }
        else{
            unset($formats['binary']);
        }
        return $formats;
    }

    // ################ Transformation ################
    private function dec_to_bin($octet): string
    {
        return str_pad(decbin($octet), 8, "0", STR_PAD_LEFT);
    }
    private function dec_to_hex($octet): string
    {
        return str_pad(dechex($octet), 2, "0", STR_PAD_LEFT);
    }
    private function bin_to_dec($octet)
    {
        return bindec($octet);
    }
    private function bin_to_hex($octet): string
    {
        return str_pad(dechex(bindec($octet)), 2, "0", STR_PAD_LEFT);
    }
    private function hex_to_dec($octet)
    {
        return hexdec($octet);
    }
    private function hex_to_bin($octet): string
    {
        return str_pad(decbin(hexdec($octet)), 8, "0", STR_PAD_LEFT);
    }

    // ################ Vérification ################
    private function is_validIPv4($liste): bool
    {
        return count($liste) === 4;
    }
    private function is_binary($octet): bool
    {
        return preg_match('/^[01]+$/', $octet) === 1;
    }
    private function is_hexadecimal($octet): bool
    {
        return preg_match('/^[0-9a-fA-F]+$/', $octet) === 1 && hexdec($octet) <= 0xFF;
    }
    private function is_decimal($octet): bool
    {
        return is_numeric($octet) && intval($octet) >= 0 && intval($octet) <= 255;
    }

}