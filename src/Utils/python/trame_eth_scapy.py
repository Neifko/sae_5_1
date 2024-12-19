from scapy.all import Ether

# Exemple de création d'une trame Ethernet
def create_ethernet_frame(dst_mac, src_mac, payload):
    return Ether(dst=dst_mac, src=src_mac) / payload
    