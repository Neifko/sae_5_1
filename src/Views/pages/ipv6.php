<link rel="stylesheet" href="/css/pages.css">
    <h2>Simplification et Extension d'une adresse IPv6</h2>
    <form >
        <label for="ipv6">Entrez une adresse IPv6 :</label>
        <input type="text" id="ipv6" placeholder="ex : 2001:db8::ff00:42:8329" required>
        <button type="submit" id="simplifier-button">Simplifier</button>
        <button type="submit" id="etendre-button">Etendre</button>
        <button id="classify-button">Classifier</button>
    </form>
    
    <div id="resultDiv"></div>


    <script src="/javascript/classe_ipv6.js"></script>

