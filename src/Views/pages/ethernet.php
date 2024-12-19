<!DOCTYPE html>
<html>
<head>
    <title>Créer une trame Ethernet</title>
</head>
<body>
    <h1>Créer une trame Ethernet</h1>
    <form action="/ethernet" method="post">
        <label for="destinationMac">Adresse MAC de destination :</label>
        <input type="text" id="destinationMac" name="destinationMac" required><br><br>

        <label for="sourceMac">Adresse MAC source :</label>
        <input type="text" id="sourceMac" name="sourceMac" required><br><br>

        <label for="ethertype">Type Ethernet (en hexadécimal) :</label>
        <input type="text" id="ethertype" name="ethertype" required><br><br>

        <button type="submit">Créer et envoyer</button>
    </form>
</body>
</html>
