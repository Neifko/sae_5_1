<link rel="stylesheet" href="/css/informations.css">
<link rel="stylesheet" href="/css/ethernet.css">
<?php
function getServerMacAddress()
{
    $mac_address = null;

    if (stristr(PHP_OS, 'WIN')) {
        // Commande pour Windows
        $output = [];
        exec('getmac', $output);
        foreach ($output as $line) {
            if (preg_match('/([A-Fa-f0-9]{2}-){5}[A-Fa-f0-9]{2}/', $line, $matches)) {
                $mac_address = $matches[0]; // Prend la première adresse MAC trouvée
                break;
            }
        }
    } else {
        // Commande pour Linux/Unix
        $output = [];
        exec("ip addr show", $output);
        foreach ($output as $line) {
            if (preg_match('/link\/ether ([A-Fa-f0-9:]{17})/', $line, $matches)) {
                $mac_address = $matches[1]; // Prend la première adresse MAC trouvée
                break;
            }
        }
    }

    return $mac_address ?? 'Adresse MAC introuvable';
}

// Exemple d'utilisation
$server_mac = getServerMacAddress();
?>
<h2>Créer une trame Ethernet</h2>
<p>Les valeurs par défaut permettent de créer une trame ethernet basic. Si vous ne cochez pas la case <code>Données personnalisées</code>
Vous devez mettre une adresse ipv4 dans <code>IPv4 pour le ping</code> et le script va effectuer un ping avec ce que vous avez
renseigné. L'adresse mac destination sera récupérée à l'aide de l'adresse ipv4 grace à un arp.
Si vous cochez la case <code>données personnalisées</code> alors le contenu de trame ethernet doit être remplie manuellement.</p>
<form action="" method="post">
    <div class="field">
        <label for="preamble">Préambule</label>
        <input type="text" id="preamble" name="preambule" value="<?= str_repeat('10101010', 7) ?>" required
               pattern="^(10101010){7}$" title="Le préambule doit être composé de 7 octets (10101010 répété 7 fois).">
        <p class="description">7 octets - Synchronisation de l’envoi. Chaque octet vaut 10101010.</p>
    </div>
    <div class="field">
        <label for="sfd">SFD</label>
        <input type="text" id="sfd" name="sfd" value="10101011" required pattern="^10101011$"
               title="Le SFD doit être exactement égal à 10101011 (1 octet).">
        <p class="description">1 octet - Indique que le début de la trame va commencer.</p>
    </div>
    <div class="field">
        <label for="dst_mac">Adresse destination</label>
        <input type="text" id="dst_mac" name="destination_mac" value="FF-FF-FF-FF-FF-FF" required
               pattern="^([0-9A-Fa-f]{2}[-:]){5}[0-9A-Fa-f]{2}$"
               title="L'adresse MAC doit être au format XX-XX-XX-XX-XX-XX où X est une valeur hexadécimale.">
        <p class="description">6 octets - Adresse MAC du destinataire ou adresse de broadcast.</p>
    </div>
    <div class="field">
        <label for="src_mac">Adresse source</label>
        <input type="text" id="src_mac" name="source_mac" value="<?php echo $server_mac; ?>" required
               pattern="^([0-9A-Fa-f]{2}[-:]){5}[0-9A-Fa-f]{2}$">
        <p class="description">6 octets - Adresse MAC du serveur qui héberge cette page.</p>
    </div>
    <div class="field">
        <label for="ether_type">Ether Type</label>
        <select id="ether_type" name="ethertype" required>
            <option value="0x6000">DEC (0x6000)</option>
            <option value="0x0609">DEC (0x0609)</option>
            <option value="0x0600">XNS (0x0600)</option>
            <option value="0x0800" selected>IPv4 (0x0800)</option>
            <option value="0x0806">ARP (0x0806)</option>
            <option value="0x8019">Domain (0x8019)</option>
            <option value="0x8035">RARP (0x8035)</option>
            <option value="0x809B">AppleTalk (0x809B)</option>
            <option value="0x86DD">IPv6 (0x86DD)</option>
        </select>
        <p class="description">2 octets - Sélectionnez le protocole (exemple : IPv4, ARP, IPv6).</p>
    </div>
    <div class="field">
        <label for="data">Données</label>
        <textarea id="data" name="data" maxlength="1500"
                  placeholder="Entrez ici les données à transmettre..."></textarea>
        <p class="description">46–1500 octets - Contient les données de la couche 3 (exemple : datagramme IP). Complétez
            avec du padding si nécessaire.</p>
    </div>
    <div class="field">
        <label for="havepayload">Données personnalisées</label>
        <input type="checkbox" id="havepayload" name="havepayload" value="true">
    </div>
    <div class="field">
        <label for="ip-ping">IPv4 pour le ping</label>
        <input type="text" id="ip-ping" name="ip_ping"
               pattern="^(25[0-5]|2[0-4][0-9]|1?[0-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1?[0-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1?[0-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1?[0-9]?[0-9])$"
        >
        <p>Ne pas cocher havepayload</p>
    </div>
    <div class="field">
        <label for="fcs">FCS</label>
        <input type="text" id="fcs" name="fcs" pattern="^[0-9A-Fa-f]{8}$"
               title="Le FCS doit être une séquence hexadécimale de 4 octets (8 caractères).">
        <p class="description">4 octets - Séquence de contrôle de trame (calcul polynomial CRC).</p>
    </div>
    <div class="field">
        <label for="interface">Interface</label>
        <input type="text" id="interface" name="interface" required value="eth0">
    </div>

    <button type="submit">Créer Trame Ethernet</button>
</form>
<div class="centerVertical">
    <?php if (isset($_SESSION['flash_message'])): ?>
        <?php echo htmlspecialchars($_SESSION['flash_message']['content']); ?>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>
</div>
<!-- Hint Information, fixed on the right -->
<label for="hint-click">
    <input type="checkbox" id="hint-click">
    <div class="hint">
        <span class="fa-info">i</span>
        <p style="height: 80%">
            Le module <strong>Trame Ethernet</strong> permet de créer une trame Ethernet personnalisée en utilisant la librairie Python <a href="https://scapy.net/" target="_blank">Scapy</a>. Ce module offre deux modes de création de trame : <strong>mode basique</strong> et <strong>mode avancé avec données personnalisées</strong>.<br>
            <br>
            Dans le <strong>mode basique</strong>, l'utilisateur renseigne simplement une adresse IPv4 dans le champ "IPv4 pour le ping". Le script génère automatiquement l'adresse MAC de destination via une requête ARP, puis crée la trame Ethernet pour effectuer un ping vers l'adresse spécifiée.<br>
            <br>
            Dans le <strong>mode avancé</strong>, lorsque la case "Données personnalisées" est activée, l'utilisateur peut remplir manuellement les champs de la trame Ethernet, y compris les données à transmettre.<br>
            <br>
            La trame Ethernet est composée des éléments suivants :<br>
            - <strong>Préambule</strong> (7 octets) : Synchronisation de l’envoi, chaque octet vaut <code>10101010</code>.<br>
            - <strong>SFD</strong> (1 octet) : Indique le début de la trame avec la valeur <code>10101011</code>.<br>
            - <strong>Adresse de destination</strong> (6 octets) : Adresse MAC du destinataire ou adresse de broadcast, exemple <code>FF-FF-FF-FF-FF-FF</code>.<br>
            - <strong>Adresse source</strong> (6 octets) : Adresse MAC du serveur qui envoie la trame, exemple <code>E4-54-E8-5C-3B-BA</code>.<br>
            - <strong>Ether Type</strong> (2 octets) : Sélection du protocole, par exemple <code>0x0800</code> pour IPv4.<br>
            - <strong>Données</strong> (46–1500 octets) : Contient les données à transmettre, comme un datagramme IP. Du padding est ajouté si nécessaire.<br>
            - <strong>FCS</strong> (4 octets) : Séquence de contrôle de trame, qui permet de vérifier l'intégrité des données grâce à un calcul polynomial CRC.<br>
            <br>
            Le module permet également de tester la connectivité en envoyant un ping avec l'adresse IPv4 renseignée. Cela est effectué en utilisant les adresses MAC obtenues grâce à une requête ARP. Pour ceux qui préfèrent personnaliser le contenu de la trame, le champ "Données personnalisées" offre une flexibilité totale dans la création de trames Ethernet.
        </p>
    </div>
</label>
<div class="centerVertical"><?php if (!empty($result)): ?>
        <?= $success_msg ?>
    <?php endif; ?>
</div>
<?php if (!empty($result)): ?>
<div class="centerVertical">
    <h3>Retour python</h3>
    <?= $result ?>
    </div>
<?php endif; ?>
