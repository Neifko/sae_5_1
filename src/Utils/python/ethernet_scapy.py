import json
import os
from scapy.all import Ether, sendp, srp, sniff, IP, ICMP


def create_ethernet_frame_from_json(json_file):
    try:
        # Lecture du fichier JSON
        with open(json_file, 'r') as file:
            data = json.load(file)

        # Validation des champs essentiels
        required_fields = ["preambule", "sfd", "destination_mac", "source_mac", "ethertype", "data"]
        for field in required_fields:
            if field not in data:
                raise ValueError(f"Le champ '{field}' est manquant dans le fichier JSON.")

        # Extraction des données du JSON
        destination_mac = convert_mac_format(data['destination_mac'])
        source_mac = convert_mac_format(data['source_mac'])
        ethertype = int(data['ethertype'], 16)  # Conversion de l'EtherType en entier
        havepayload = bool(data['havepayload'])
        payload = data['data']  # Les données (payload)


        # Construction de la trame Ethernet
        frame = Ether(dst=destination_mac, src=source_mac, type=ethertype)

        if (havepayload):
            frame = frame / bytes(payload, 'utf-8')
        else:
            action = data['action']
            if (action == "ping"):
                ip_ping = data['ip_ping']

                # Construction du paquet IP + ICMP
                ip_packet = IP(dst=ip_ping)
                icmp_packet = ICMP()  # Requête ICMP (type=8, code=0 par défaut)

                # Assemblage de la trame complète
                frame = frame / ip_packet / icmp_packet
            else:
                print("Action non supporté ou erreur")

        return frame

    except Exception as e:
        print(f"Erreur lors de la création de la trame Ethernet : {e}")
        return None
    
def convert_mac_format(mac_address):
    """
    Convertit une adresse MAC du format avec des tirets (AA-AA-AA-AA-AA-AA)
    au format avec des deux-points (AA:AA:AA:AA:AA:AA).
    """
    # Vérifie que l'adresse MAC est valide
    if "-" in mac_address:
        # Convertit les tirets en deux-points
        return mac_address.replace("-", ":").lower()
    return mac_address.lower()

def main(directory):
    # Création de la trame Ethernet depuis le fichier JSON
    ethernet_frame = create_ethernet_frame_from_json(directory)

    if ethernet_frame:
        # Affichage de la trame Ethernet
        print("Trame Ethernet générée :")
        print(ethernet_frame.show(dump=True))

        # Envoi de la trame (optionnel, commenter si non nécessaire)
        # sendp(ethernet_frame, iface="eth0")  # Spécifiez l'interface réseau si nécessaire
        print("Trame prête à être envoyée.")

        # Spécifiez l'interface réseau pour l'envoi de la trame
        interface = "eth0"  # Remplacez par le nom de votre interface réseau

        try:
            # Envoi de la trame Ethernet
            print(f"Envoi de la trame sur l'interface {interface}...")
            answered, unanswered = srp(ethernet_frame, iface=interface, timeout=10, verbose=True)
            print("Trame envoyée avec succès.")

            # Traitement des réponses
            if answered:
                print("Réponses reçues :")
                for sent, received in answered:
                    print(f"Envoyé : {sent.summary()}")
                    print(f"Reçu : {received.summary()}")
            else:
                print("Aucune réponse reçue.")

            # Traitement des trames sans réponse
            if unanswered:
                print("Trames non répondues :")
                for sent in unanswered:
                    print(f"Non répondu : {sent.summary()}")




        except PermissionError:
            print("Erreur : Vous devez exécuter ce script avec des privilèges root (sudo).")
        except Exception as e:
            print(f"Erreur lors de l'envoi de la trame : {e}")

if __name__ == "__main__":
    current_dir = os.path.dirname(os.path.abspath(__file__))
    json_dir = os.path.join(current_dir, "..", "..", "..", "public","trame_data.json")
    main(json_dir)
