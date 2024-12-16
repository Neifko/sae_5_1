<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Module IPv4</title>
    <style>
        #result {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            display: none;
        }
        #result.success {
            border-color: green;
            color: green;
        }
        #result.error {
            border-color: red;
            color: red;
        }
    </style>
</head>

<body>
    <h1>Connaitre le masque de l'IPv4</h1>
    <p>Entrez une adresse IPv4 pour connaître sa classe et son masque associé.</p>
    
    <form id="ipv4-form" action="/ipv4/convert" method="POST">
        <label for="ipv4_address">Adresse IPv4 :</label>
        <input type="text" id="ipv4_address" name="ipv4_address" placeholder="ex : 192.168.1.1" required>
        <button type="submit">Convertir</button>
    </form>

    <div id="result"></div>

    <script src="/javascript/classe_ipv4.js"></script>
</body>
</html>
