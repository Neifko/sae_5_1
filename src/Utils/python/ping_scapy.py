#!/usr/bin/env python3
import sys
from scapy.all import sr1, IP, ICMP
import socket

def ping(ip):
    try:
        print(f"Ping en cours vers l'adresse IP: {ip}")
        # Créer un paquet ICMP pour IPv4
        packet = IP(dst=ip)/ICMP()

        # Envoyer le paquet et attendre une réponse
        reply = sr1(packet, timeout=2, verbose=0)

        if reply:
            return f"Réponse reçue de {ip}: TTL={reply.ttl} Temps={reply.time*1000:.2f} ms"
        else:
            return f"Aucune réponse de {ip}. Le ping a échoué."

    except Exception as e:
        return f"Erreur lors du ping : {e}"

def resolve_domain(domain):
    try:
        # Résoudre un nom de domaine en adresse IP (IPv4)
        ip = socket.gethostbyname(domain)
        return ip
    except socket.gaierror:
        return None

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage : python3 ping_scapy.py <adresse_ip_ou_nom_de_domaine>")
        sys.exit(1)

    ip_address_or_domain = sys.argv[1]
    
    # Vérifier si l'entrée est un nom de domaine ou une IP
    if '.' in ip_address_or_domain:  # Vérification si c'est un nom de domaine ou une adresse IPv4
        ip_address = resolve_domain(ip_address_or_domain)
        
        if ip_address:
            print(f"Nom de domaine résolu : {ip_address}")
            print(ping(ip_address))
        else:
            print(f"Impossible de résoudre le nom de domaine : {ip_address_or_domain}")
            sys.exit(1)
    elif ip_address_or_domain.count('.') == 3:  # Vérification pour une adresse IPv4
        print(ping(ip_address_or_domain))
    else:
        print("Entrée invalide. Veuillez entrer une adresse IP ou un nom de domaine valide.")
        sys.exit(1)
