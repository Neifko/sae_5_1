<link rel="stylesheet" href="/css/pages.css">

<h2>Connaitre le masque de l'IPv4</h2>

<form id="ipv4-form" action="/ipv4/convert" method="POST">
    <label for="">Entrez une adresse IPv4 pour connaître sa classe et son masque associé.</label>
    <label for="ipv4_address">Adresse IPv4 :</label>
    <input type="text" id="ipv4_address" name="ipv4_address" placeholder="ex : 192.168.1.1" required>
    <button type="submit">Convertir</button>
</form>

<div id="centerVertical">
    <div id="result"></div>
</div>



<script src="/javascript/classe_ipv4.js"></script>

