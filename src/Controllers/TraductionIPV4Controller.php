<?php

namespace Victor\Sae51\Controllers;

use Victor\Sae51\Config\Database;
use Victor\Sae51\Middleware\AuthMiddleware;

class TraductionIPV4Controller
{
    public function module_traduction_get()
    {
        $title = "Module de Traduction IPV4";
        include __DIR__ . '/../Views/module_traduction_ipv4.php';
    }
    public function module_traduction_post()
    {
        $title = "Module de Traduction IPV4";
        include __DIR__ . '/../Views/module_traduction_ipv4.php';
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
        unset($formats[$format_detecte]); // Exclure le format détecté d'origine
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