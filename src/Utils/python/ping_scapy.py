"""
File ping_scapy.py : sae_5_1/src/Utils/python/ping_scapy.py
"""


import sys
from scapy.all import sr1, IP, ICMP
import socket
import ipaddress

def ping(ip):
    try:
        # Créer un paquet ICMP pour IPv4
        packet = IP(dst=ip)/ICMP()

        # Envoyer le paquet et attendre une réponse
        reply = sr1(packet, timeout=2, verbose=0)

        if reply:
            ttl = reply.ttl
            time_ms = reply.time 
            return f"TTL={ttl}, Temps={time_ms:.2f} ms"
        else:
            return "Aucune réponse. Le ping a échoué."

    except Exception as e:
        return f"Erreur lors du ping : {e}"

def resolve_domain(domain):
    try:
        # Résoudre un nom de domaine en adresse IP (IPv4)
        ip = socket.gethostbyname(domain)
        return ip
    except socket.gaierror:
        return None

def reverse_lookup(ip):
    try:
        # Recherche inverse pour résoudre une IP en nom de domaine
        domain = socket.gethostbyaddr(ip)[0]
        return domain
    except socket.herror:
        return None

def get_ip_info(ip):
    try:
        ip_obj = ipaddress.ip_address(ip)
        if ip_obj.is_private:
            return "Privée"
        else:
            return "Publique"
    except ValueError:
        return "Invalide"

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage : python3 ping_scapy.py <adresse_ip_ou_nom_de_domaine>")
        sys.exit(1)

    ip_address_or_domain = sys.argv[1]

    if '.' in ip_address_or_domain:  # Vérification si c'est un nom de domaine ou une adresse IPv4
        if ip_address_or_domain.count('.') == 3:  # Entrée d'une adresse IP
            ip_address = ip_address_or_domain
            domain = reverse_lookup(ip_address)
            ip_type = get_ip_info(ip_address)
            ping_result = ping(ip_address)
            print(f"Adresse IP : {ip_address}\nNom de domaine : {domain if domain else 'Non résolu'}\nType : {ip_type}\nResultats : {ping_result}")
        else:  # Entrée d'un nom de domaine
            domain = ip_address_or_domain
            ip_address = resolve_domain(domain)
            if ip_address:
                ip_type = get_ip_info(ip_address)
                ping_result = ping(ip_address)
                print(f"Nom de domaine : {domain}\nAdresse IP : {ip_address}\nType : {ip_type}\nResultats : {ping_result}")
            else:
                print(f"Impossible de résoudre le nom de domaine : {domain}")
                sys.exit(1)
    else:
        print("Entrée invalide. Veuillez entrer une adresse IP ou un nom de domaine valide.")
        sys.exit(1)
