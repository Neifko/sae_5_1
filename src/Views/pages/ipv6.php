<link rel="stylesheet" href="/css/pages.css">
<link rel="stylesheet" href="/css/informations.css">

    <h2>Simplification et Extension d'une adresse IPv6</h2>      
    <form >
        <label for="ipv6">Entrez une adresse IPv6 :</label>
        <input type="text" id="ipv6" placeholder="ex : 2001:db8::ff00:42:8329" required>
        <button type="submit" id="simplifier-button">Simplifier</button>
        <button type="submit" id="etendre-button">Etendre</button>
        <button id="classify-button">Classifier</button>
    </form>
    <!-- Hint Information, fixed on the right -->
<label for="hint-click">
    <input type="checkbox" id="hint-click">
    <div class="hint">
        <span class="fa-info">i</span>
        <p>
            L’IPv6 (Internet Protocol version 6) est une version du protocole Internet qui permet d’identifier les appareils sur un réseau à l'aide d'adresses plus longues et plus complexes que celles de l'IPv4. Une adresse IPv6 se compose de huit groupes de quatre chiffres hexadécimaux, séparés par des deux-points (ex. : <code>2001:0db8:85a3:0000:0000:8a2e:0370:7334</code>).<br>
            <br>
            Contrairement à l'IPv4, l'IPv6 offre une plus grande capacité d'adressage, mais sa notation peut sembler complexe. Il existe donc une écriture étendue et simplifiée pour faciliter sa lecture et sa gestion. Dans l'écriture étendue, tous les zéros sont explicitement écrits, tandis que dans l'écriture simplifiée, les zéros redondants peuvent être omis. Par exemple, l'adresse ci-dessus peut être simplifiée en : <code>2001:db8:85a3::8a2e:370:7334</code>.<br>
            <br>
            Classification des adresses IPv6 :<br>
            - Les adresses IPv6 peuvent être classées en plusieurs catégories :<br>
                - **Link-Local** : Ce type d'adresse est utilisé pour la communication au sein d'un même réseau local, souvent avec une portée limitée à un seul segment de réseau (ex. : <code>fe80::1</code>).<br>
                - **Globale** : Ce type d'adresse est destiné à l'Internet global, et est routable à travers tous les réseaux. Exemple : <code>2001:0db8::/32</code>.<br>
                - **Locale Unique (LUA)** : Ce type d'adresse est réservé pour des usages privés, généralement pour des réseaux internes. Exemple : <code>fd00::/8</code>.<br>
                - **Non-classifiable** : Certaines adresses IPv6 ne rentrent dans aucune des catégories ci-dessus et sont donc non classifiables.<br>
            <br>
            Fonctionnement du module :<br>
            - Le module attend en entrée une adresse IPv6 sous sa forme simplifiée ou étendue.<br>
            - Le module simplifie l'adresse IPv6 en supprimant les zéros redondants selon la notation simplifiée.<br>
            - Le module peut également fournir l'écriture étendue complète si nécessaire.<br>
            - Le module classe l'adresse IPv6 selon son type (Link-Local, Globale, Locale Unique, etc.).<br>
        </p>

    </div>
</label>

    <div id="resultDiv"></div>


    <script src="/javascript/classe_ipv6.js"></script>

