<?php
// Détection de l'adresse MAC du serveur
$server_ip = $_SERVER['SERVER_ADDR'];
$server_mac = exec("arp -n | grep $server_ip | awk '{print $3}'"); // Commande pour récupérer l'adresse MAC sur un système Unix/Linux
?>
<h1>Créer une trame Ethernet</h1>
    <form action="" method="post">
        <div class="field">
            <label for="preamble">5.1 – Préambule</label>
            <input type="text" id="preamble" name="preamble" value="10101010" required>
            <p class="description">7 octets - Synchronisation de l’envoi. Chaque octet vaut 10101010.</p>
        </div>
        <div class="field">
            <label for="sfd">5.2 – SFD</label>
            <input type="text" id="sfd" name="sfd" value="10101011" required>
            <p class="description">1 octet - Indique que le début de la trame va commencer.</p>
        </div>
        <div class="field">
            <label for="dst_mac">5.3 – Adresse destination</label>
            <input type="text" id="dst_mac" name="dst_mac" value="FF-FF-FF-FF-FF-FF" required>
            <p class="description">6 octets - Adresse MAC du destinataire ou adresse de broadcast.</p>
        </div>
        <div class="field">
            <label for="src_mac">5.4 – Adresse source</label>
            <input type="text" id="src_mac" name="src_mac" value="<?php echo $server_mac; ?>" readonly required>
            <p class="description">6 octets - Adresse MAC du serveur qui héberge cette page.</p>
        </div>
        <div class="field">
            <label for="ether_type">5.5 – Ether Type</label>
            <select id="ether_type" name="ether_type" required>
                <option value="0x0800">IPv4 (0x0800)</option>
                <option value="0x86DD">IPv6 (0x86DD)</option>
                <option value="0x0806">ARP (0x0806)</option>
                <option value="0x8035">RARP (0x8035)</option>
                <option value="0x8100">802.1Q VLAN (0x8100)</option>
                <option value="0x809B">AppleTalk (0x809B)</option>
                <option value="0x0600">Xerox NS (XNS) (0x0600)</option>
                <option value="0x0805">X.25 (0x0805)</option>
            </select>
            <p class="description">2 octets - Sélectionnez le protocole (exemple : IPv4, IPv6, ARP).</p>
        </div>
        <div class="field">
            <label for="data">5.7 – Données</label>
            <input type="text" id="data" name="data" required>
            <p class="description">46–1500 octets - Contient les données de la couche 3 (exemple : datagramme IP).</p>
        </div>
        <div class="field">
            <label for="fcs">5.8 – FCS</label>
            <input type="text" id="fcs" name="fcs" required>
            <p class="description">4 octets - Séquence de contrôle de trame (calcul polynomial CRC).</p>
        </div>
        <button type="submit">Créer Trame Ethernet</button>
    </form>
    <div>
        <a href="/scapy">Retour</a>
    </div>

