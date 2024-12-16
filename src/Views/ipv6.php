<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Simplification et Extension IPv6</title>
</head>
<body>
    <h1>Simplification et Extension d'une adresse IPv6</h1>
    <form id="ipv6-form">
        <label for="ipv6">Entrez une adresse IPv6 :</label>
        <input type="text" id="ipv6" placeholder="ex : 2001:db8::ff00:42:8329" required>
        <button type="submit" id="simplifier_button">Simplifier</button>
        <button type="submit" id="etendre_button">Etendre</button>
    </form>
    
    <div id="result"></div>

    <script src="/javascript/classe_ipv6.js"></script>
</body>
</html>
