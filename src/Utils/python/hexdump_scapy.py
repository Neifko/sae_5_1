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
    """
    Analyse les données brutes (en hexadécimal) et affiche les résultats.
    """
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

def create_sample_packet(dst_ip):
    """
    Crée un exemple de paquet IP vers une adresse donnée et affiche les résultats.
    """
    packet = IP(dst=dst_ip) / b"Exemple de données"
    result = {
        "summary": packet.summary(),
        "hexdump": hexdump(packet, dump=True)
    }
    print(json.dumps(result, indent=4))

def compare_data(data1, data2):
    """
    Compare deux ensembles de données brutes en hexadécimal et affiche les résultats.
    """
    try:
        packet1 = Ether(bytes.fromhex(data1.replace(" ", "")))
        packet2 = Ether(bytes.fromhex(data2.replace(" ", "")))
        result = {
            "packet1": {
                "summary": packet1.summary(),
                "hexdump": hexdump(packet1, dump=True)
            },
            "packet2": {
                "summary": packet2.summary(),
                "hexdump": hexdump(packet2, dump=True)
            },
            "comparison": "Les paquets sont identiques." if packet1 == packet2 else "Les paquets sont différents."
        }
        print(json.dumps(result, indent=4))
    except Exception as e:
        error_message = {"error": f"Erreur lors de la comparaison des données : {str(e)}"}
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
    elif args.command == "sample":
        create_sample_packet(args.dst_ip)
    elif args.command == "compare":
        compare_data(args.data1, args.data2)
    else:
        parser.print_help()
