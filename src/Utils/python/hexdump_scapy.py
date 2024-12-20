"""
File hexdump_scapy.py : sae_5_1/src/Utils/python/hexdump_scapy.py
"""


import argparse
import json
from scapy.all import hexdump, sniff, IP, Ether, Packet

def capture_packets(interface):
    """
    Capture les paquets réseau sur une interface donnée et affiche les résultats.
    """
    packets = sniff(iface=interface, count=10)  # Capture de 10 paquets pour cet exemple
    results = []
    for packet in packets:
        results.append({
            "summary": packet.summary(),
            "hexdump": hexdump(packet, dump=True)
        })
    print(json.dumps(results, indent=4))

def analyze_data(raw_data):
    try:
        packet = Ether(bytes.fromhex(raw_data.replace(" ", "")))
        result = {
            "summary": packet.summary(),
            "hexdump": hexdump(packet, dump=True)
        }
        print(json.dumps(result, indent=4))
    except Exception as e:
        error_message = {"error": f"Erreur lors de l'analyse des données : {str(e)}"}
        print(json.dumps(error_message, indent=4))

def analyze_pcap(file_path):
    try:
        packets = rdpcap(file_path)
        results = []
        for packet in packets:
            results.append({
                "summary": packet.summary(),
                "hexdump": hexdump(packet, dump=True)
            })
        print(json.dumps(results, indent=4))
    except Exception as e:
        error_message = {"error": f"Erreur lors de l'analyse du fichier pcap : {str(e)}"}
        print(json.dumps(error_message, indent=4))


def create_sample_packet(dst_ip):
    """
    Crée un exemple de paquet IP vers une adresse donnée et affiche les résultats.
    """
    try:
        packet = IP(dst=dst_ip) / b"Example data"  # Remplacer par des caractères compatibles ASCII
        result = {
            "summary": packet.summary(),
            "hexdump": hexdump(packet, dump=True)
        }
        print(json.dumps(result, indent=4))
    except Exception as e:
        error_message = {"error": f"Erreur lors de la création du paquet : {str(e)}"}
        print(json.dumps(error_message, indent=4))



if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Hexdump Scapy Utility")
    subparsers = parser.add_subparsers(dest="command", help="Sous-commandes disponibles")

    # Capture de paquets
    parser_capture = subparsers.add_parser("capture", help="Capture des paquets réseau")
    parser_capture.add_argument("--interface", required=True, help="Interface réseau pour la capture")

    # Analyse des données brutes
    parser_analyze = subparsers.add_parser("analyze", help="Analyse des données brutes")
    parser_analyze.add_argument("--data", required=True, help="Données brutes (hexadécimal) à analyser")

    # Création d’un exemple de paquet
    parser_sample = subparsers.add_parser("sample", help="Créer un exemple de paquet")
    parser_sample.add_argument("--dst_ip", required=True, help="Adresse IP de destination")

    # Comparaison de deux ensembles de données
    parser_compare = subparsers.add_parser("compare", help="Comparer deux ensembles de données")
    parser_compare.add_argument("--data1", required=True, help="Premier ensemble de données brutes")
    parser_compare.add_argument("--data2", required=True, help="Deuxième ensemble de données brutes")

    # Parsing des arguments
    args = parser.parse_args()

    if args.command == "capture":
        capture_packets(args.interface)
    elif args.command == "analyze":
        analyze_data(args.data)
    elif args.command == "analyze_pcap":
        analyze_pcap(args.file)
    elif args.command == "sample":
        create_sample_packet(args.dst_ip)
    else:
        parser.print_help()
