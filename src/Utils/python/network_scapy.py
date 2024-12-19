#!/usr/bin/env python3
import os
import json
from scapy.all import get_if_addr, get_if_hwaddr, get_if_list

def get_network_info():
    """
    Récupère les informations réseau pour toutes les interfaces disponibles.
    Retourne un dictionnaire avec les détails de chaque interface.
    """
    interfaces = get_if_list()
    network_info = {}

    for interface in interfaces:
        ip_address = get_if_addr(interface)
        mac_address = get_if_hwaddr(interface)
        usage = determine_interface_usage(interface)

        network_info[interface] = {
            "Adresse IP": ip_address if ip_address else "Non attribuée",
            "Adresse MAC": mac_address,
            "Usage prévu": usage
        }

    return network_info

def determine_interface_usage(interface):
    """
    Détermine une utilisation probable de l'interface en fonction de son nom.
    """
    interface = interface.lower()
    if "eth" in interface:
        return "Connexion filaire (Ethernet) : Interface pour connexions réseau stables et rapides via câble."
    elif any(keyword in interface for keyword in ["wl", "wifi", "wlan"]):
        return "Connexion sans fil (Wi-Fi) : Interface pour connexions réseau sans fil à un routeur."
    elif any(keyword in interface for keyword in ["lo", "loopback"]):
        return "Interface loopback : Utilisée pour les tests locaux et la communication interne au système."
    elif "docker" in interface:
        return "Interface Docker : Permet la connectivité réseau pour conteneurs et environnements virtuels."
    elif any(keyword in interface for keyword in ["br", "bridge"]):
        return "Pont réseau (bridge) : Relie plusieurs interfaces, souvent utilisé en virtualisation."
    elif "vmnet" in interface:
        return "Interface VMware (machine virtuelle)"
    elif "vbox" in interface:
        return "Interface VirtualBox (machine virtuelle)"
    elif "tun" in interface or "tap" in interface:
        return "Interface VPN ou tunnel"
    elif "ppp" in interface:
        return "Connexion point à point (modem ou VPN)"
    elif "bond" in interface:
        return "Interface d'agrégation de liens (bonding)"
    elif "usb" in interface:
        return "Connexion réseau via USB"
    elif "enp" in interface or "ens" in interface:
        return "Interface réseau moderne (Ethernet)"
    else:
        return "Interface inconnue ou spécifique"

if __name__ == "__main__":
    # Déterminer le chemin absolu vers le fichier JSON dans Views/data
    current_dir = os.path.dirname(os.path.abspath(__file__))
    json_dir = os.path.join(current_dir, "..", "..", "Views", "data")
    os.makedirs(json_dir, exist_ok=True)
    print("Création du fichier JSON")
    json_path = os.path.join(json_dir, "network_info.json")


    # Récupérer les informations réseau et les écrire dans le fichier JSON
    network_info = get_network_info()
    try:
        with open(json_path, "w", encoding="utf-8") as json_file:
            json.dump(network_info, json_file, ensure_ascii=False, indent=4)
        print(f"Fichier JSON généré avec succès : {json_path}")
    except Exception as e:
        print(f"Erreur lors de la génération du fichier JSON : {e}")
