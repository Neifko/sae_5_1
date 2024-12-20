import json
from scapy.all import Ether, sendp

# Chemin vers le fichier JSON généré par le script PHP
JSON_FILE = "trame_data.json"

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
        destination_mac = data['destination_mac']
        source_mac = data['source_mac']
        ethertype = int(data['ethertype'], 16)  # Conversion de l'EtherType en entier
        payload = data['data']  # Les données (payload)

        # Construction de la trame Ethernet
        frame = Ether(dst=destination_mac, src=source_mac, type=ethertype) / bytes(payload, 'utf-8')

        return frame

    except Exception as e:
        print(f"Erreur lors de la création de la trame Ethernet : {e}")
        return None

def main():
    # Création de la trame Ethernet depuis le fichier JSON
    ethernet_frame = create_ethernet_frame_from_json(JSON_FILE)

    if ethernet_frame:
        # Affichage de la trame Ethernet
        print("Trame Ethernet générée :")
        print(ethernet_frame.show(dump=True))

        # Envoi de la trame (optionnel, commenter si non nécessaire)
        # sendp(ethernet_frame, iface="eth0")  # Spécifiez l'interface réseau si nécessaire
        print("Trame prête à être envoyée.")

if __name__ == "__main__":
    main()