<link rel="stylesheet" href="/css/global.css">
<link rel="stylesheet" href="/css/informations.css">

<h2>Connaitre le masque de l'IPv4</h2>

<form id="ipv4-form" action="/ipv4/convert" method="POST">
    <label for="">Entrez une adresse IPv4 pour connaître sa classe et son masque associé.</label>
    <label for="ipv4_address">Adresse IPv4 :</label>
    <input type="text" id="ipv4_address" name="ipv4_address" value="192.168.1.1" placeholder="ex : 192.168.1.1" required>
    <button type="submit">Appliquer</button>
</form>

<!-- Hint Information, fixed on the right -->
<label for="hint-click">
    <input type="checkbox" id="hint-click">
    <div class="hint">
        <span class="fa-info">i</span>
        <p>
            L’IPv4 (Internet Protocol version 4) est un protocole utilisé pour identifier les appareils sur un réseau
            via une adresse composée de quatre nombres séparés par des points (ex. : <code>192.168.1.1</code>).<br>
            <br>
            Chaque adresse appartient à une classe (A, B, C, etc.), définissant sa plage et son masque par défaut.<br>
            <br>
            Fonctionnement du module :<br>
            - Le module attend en entrée une adress ipv4 sans le masque ("/")<br>
            - Le module calcule la classe<br>
            - Le module calcule le masque<br>
            - Le module fournit une notation CIDR<br>
            
        </p>
    </div>
</label>

<div class="centerVertical">
    <div id="result"></div>
</div>



<script src="/javascript/classe_ipv4.js"></script>

