import argparse
import json
from scapy.all import hexdump, sniff, IP, Ether, rdpcap, wrpcap, Packet

def capture_packets(interface, output_file="capture.json"):
    """
    Capture les paquets réseau sur une interface donnée et les sauvegarde dans un fichier JSON.
    """
    packets = sniff(iface=interface, count=10)  # Capture de 10 paquets pour cet exemple
    results = []
    for packet in packets:
        results.append({
            "summary": packet.summary(),
            "hexdump": hexdump(packet, dump=True)
        })
    with open(output_file, "w") as f:
        json.dump(results, f, indent=4)
    print(f"Paquets capturés sauvegardés dans {output_file}")

def analyze_data(raw_data, output_file="simple_analyze.json"):
    """
    Analyse les données brutes (en hexadécimal) et les sauvegarde dans un fichier JSON.
    """
    try:
        packet = Ether(bytes.fromhex(raw_data.replace(" ", "")))
        result = {
            "summary": packet.summary(),
            "hexdump": hexdump(packet, dump=True)
        }
        with open(output_file, "w") as f:
            json.dump(result, f, indent=4)
        print(f"Analyse des données brutes sauvegardée dans {output_file}")
    except Exception as e:
        print(f"Erreur lors de l'analyse des données : {e}")

def create_sample_packet(dst_ip, output_file="packet.json"):
    """
    Crée un exemple de paquet IP vers une adresse donnée.
    """
    packet = IP(dst=dst_ip) / b"Exemple de données"
    result = {
        "summary": packet.summary(),
        "hexdump": hexdump(packet, dump=True)
    }
    with open(output_file, "w") as f:
        json.dump(result, f, indent=4)
    print(f"Exemple de paquet sauvegardé dans {output_file}")

def compare_data(data1, data2, output_file="double_analyze.json"):
    """
    Compare deux ensembles de données brutes en hexadécimal et les sauvegarde dans un fichier JSON.
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
        with open(output_file, "w") as f:
            json.dump(result, f, indent=4)
        print(f"Résultat de la comparaison sauvegardé dans {output_file}")
    except Exception as e:
        print(f"Erreur lors de la comparaison des données : {e}")

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Hexdump Scapy Utility")
    subparsers = parser.add_subparsers(dest="command", help="Sous-commandes disponibles")

    # Capture de paquets
    parser_capture = subparsers.add_parser("capture", help="Capture des paquets réseau")
    parser_capture.add_argument("--interface", required=True, help="Interface réseau pour la capture")

    # Analyse des données brutes
    parser_analyze = subparsers.add_parser("analyze", help="Analyse des données brutes")
    parser_analyze.add_argument("--data", required=True, help="Données brutes (hexadécimal) à analyser")

    # Création d'un exemple de paquet
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
