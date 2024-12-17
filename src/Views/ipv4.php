

    <h1>Connaitre le masque de l'IPv4</h1>
    <p>Entrez une adresse IPv4 pour connaître sa classe et son masque associé.</p>
    
    <form id="ipv4-form" action="/ipv4/convert" method="POST">
        <label for="ipv4_address">Adresse IPv4 :</label>
        <input type="text" id="ipv4_address" name="ipv4_address" placeholder="ex : 192.168.1.1" required>
        <button type="submit">Convertir</button>
    </form>

    <div id="result"></div>

    <script src="/javascript/classe_ipv4.js"></script>

