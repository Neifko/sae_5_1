from scapy.all import IP, TCP, ICMP, sr1, send
import socket
import sys

def validate_ip(ip):
    """Valide si l'IP est une adresse IPv4 valide."""
    try:
        socket.inet_aton(ip)
        return True
    except:
        return False

def is_private_ip(ip):
    """Détermine si une adresse IP est privée."""
    private_ranges = [
        ('10.0.0.0', '10.255.255.255'),
        ('172.16.0.0', '172.31.255.255'),
        ('192.168.0.0', '192.168.255.255')
    ]
    ip_as_int = int.from_bytes(socket.inet_aton(ip), 'big')
    for start, end in private_ranges:
        if int.from_bytes(socket.inet_aton(start), 'big') <= ip_as_int <= int.from_bytes(socket.inet_aton(end), 'big'):
            return True
    return False

def get_hostname(ip):
    """Essaye de résoudre le nom d'hôte de l'adresse IP."""
    try:
        return socket.gethostbyaddr(ip)[0]
    except:
        return "Inconnu"

def ping_latency(ip):
    """Teste la latence (ping ICMP) vers l'IP cible."""
    try:
        icmp_packet = IP(dst=ip) / ICMP()
        response = sr1(icmp_packet, timeout=2, verbose=False)
        if response:
            return f"{response.time * 1000:.2f} ms"
        else:
            return "Aucune réponse (timeout)"
    except:
        return "Ping échoué"

def create_tcp_connection(target_ip, target_port):
    """Teste une connexion TCP sur une IP et un port donnés."""
    if not validate_ip(target_ip):
        return f"Erreur : {target_ip} n'est pas une adresse IP valide."
    
    if target_port < 1 or target_port > 65535:
        return f"Erreur : {target_port} n'est pas un port valide. (Doit être entre 1 et 65535)"
    
    try:
        # Crée un paquet SYN
        syn_packet = IP(dst=target_ip) / TCP(dport=target_port, flags="S")
        # Envoie le paquet et reçoit une réponse
        response = sr1(syn_packet, timeout=2, verbose=False)
        
        if response and response.haslayer(TCP):
            if response[TCP].flags == "SA":
                # Envoie un ACK pour établir la connexion (optionnel)
                ack_packet = IP(dst=target_ip) / TCP(dport=target_port, flags="A", seq=response.ack, ack=response.seq + 1)
                send(ack_packet, verbose=False)
                return f"Port ouvert : Connexion réussie et établie sur {target_ip}:{target_port}"
            else:
                return f"Port fermé ou filtré sur {target_ip}:{target_port}"
        else:
            return f"Aucune réponse de {target_ip}:{target_port} (timeout ou filtré)"
    except Exception as e:
        return f"Erreur : {e}"

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python tcp_connect.py <IP cible> <Port cible>")
        sys.exit(1)

    target_ip = sys.argv[1]
    try:
        target_port = int(sys.argv[2])
    except ValueError:
        print("Erreur : Le port doit être un entier.")
        sys.exit(1)
    
    # Validation de l'adresse IP
    if not validate_ip(target_ip):
        print(f"Erreur : {target_ip} n'est pas une adresse IP valide.")
        sys.exit(1)
    
    # Informations sur l'adresse IP
    print(f"Adresse cible : {target_ip}")
    print(f"Port cible : {target_port}")
    print(f"Type d'adresse : {'Privée' if is_private_ip(target_ip) else 'Publique'}")
    print(f"Nom d'hôte : {get_hostname(target_ip)}")
    print(f"Latence (ping) : {ping_latency(target_ip)}")
    
    # Test de connexion TCP
    result = create_tcp_connection(target_ip, target_port)
    print(result)
