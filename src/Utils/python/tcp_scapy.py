from scapy.all import IP, TCP, sr1
import socket
import sys
import time

def tcp_latency(ip, port):
    """Teste la latence (connexion TCP) vers l'IP cible et le port."""
    try:
        start_time = time.time()  # Enregistrement du temps de départ
        # Crée un paquet SYN pour initier la connexion
        syn_packet = IP(dst=ip) / TCP(dport=port, flags="S")
        response = sr1(syn_packet, verbose=False, timeout=2)

        if response and response.haslayer(TCP):
            # Si la réponse contient un flag "SA" (SYN-ACK), cela signifie que le port est ouvert
            end_time = time.time()  # Enregistrement du temps de fin
            latency = (end_time - start_time) * 1000  # Latence en millisecondes
            return f"{latency:.2f} ms"
        else:
            return "Aucune réponse (timeout ou filtré)"
    except Exception as e:
        return f"Erreur : {e}"

def get_hostname(ip):
    """Essaye de résoudre le nom d'hôte de l'adresse IP."""
    try:
        return socket.gethostbyaddr(ip)[0]
    except:
        return "Inconnu"

def resolve_ip(target):
    """Résout un nom de domaine en adresse IP si nécessaire."""
    try:
        # Si c'est un nom de domaine, le résoudre en IP
        ip = socket.gethostbyname(target)
        return ip
    except socket.gaierror:
        return None  # L'adresse IP n'a pas pu être résolue

def is_valid_ip(ip):
    """Vérifie si l'adresse est une adresse IP valide."""
    try:
        socket.inet_aton(ip)  # Vérifie si c'est une IP valide
        return True
    except socket.error:
        return False

if __name__ == "__main__":
    # Vérification des arguments (IP ou domaine et port doivent être passés en arguments)
    if len(sys.argv) != 3:
        print("Erreur : Usage: python scapy_tcp.py <IP cible ou domaine> <Port cible>")
        sys.exit(1)

    target = sys.argv[1]  # IP ou nom d'hôte
    try:
        target_port = int(sys.argv[2])
    except ValueError:
        print("Erreur : Le port doit être un entier valide.")
        sys.exit(1)

    # Résoudre l'IP si l'entrée est un nom de domaine
    if is_valid_ip(target):  # Si c'est déjà une IP
        target_ip = target
    else:
        target_ip = resolve_ip(target)  # Résoudre le domaine en IP
        if not target_ip:
            print("Erreur : Impossible de résoudre l'hôte en adresse IP.")
            sys.exit(1)

    # Test de la latence (connexion TCP)
    latency = tcp_latency(target_ip, target_port)

    # Vérification si une réponse a été reçue
    if "Aucune réponse" not in latency:
        print("Connexion réussie")
        print(f"Adresse IP : {target_ip}:{target_port}")
        print(f"Nom d'Hôte : {get_hostname(target_ip)}")
        print(f"Latence : {latency}")
    else:
        print("Aucune réponse")
        print(f"Adresse IP : {target_ip}:{target_port}")
        print(f"Nom d'Hôte : {get_hostname(target_ip)}")
        print(f"Latence : {latency}")
