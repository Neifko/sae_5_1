from scapy.all import IP, TCP, sr1, send
import sys

def validate_ip(ip):
    """Valide si l'IP est une adresse IPv4 valide."""
    try:
        import socket
        socket.inet_aton(ip)
        return True
    except:
        return False

def create_tcp_connection(target_ip, target_port, timeout=2):
    """Teste une connexion TCP sur une IP et un port donnés."""
    if not validate_ip(target_ip):
        return f"Erreur : {target_ip} n'est pas une adresse IP valide."
    
    if target_port < 1 or target_port > 65535:
        return f"Erreur : {target_port} n'est pas un port valide. (Doit être entre 1 et 65535)"
    
    try:
        # Crée un paquet SYN
        syn_packet = IP(dst=target_ip) / TCP(dport=target_port, flags="S")
        # Envoie le paquet et reçoit une réponse
        response = sr1(syn_packet, timeout=timeout, verbose=False)
        
        if response and response.haslayer(TCP):
            if response[TCP].flags == "SA":
                # Envoie un ACK pour établir la connexion (optionnel)
                ack_packet = IP(dst=target_ip) / TCP(dport=target_port, flags="A", seq=response.ack, ack=response.seq + 1)
                send(ack_packet, verbose=False)
                return f"Connexion réussie et établie sur {target_ip}:{target_port}"
            else:
                return f"Connexion refusée sur {target_ip}:{target_port}"
        else:
            return f"Aucune réponse de {target_ip}:{target_port}"
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
    
    result = create_tcp_connection(target_ip, target_port)
    print(result)
