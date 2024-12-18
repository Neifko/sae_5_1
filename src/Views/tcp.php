<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion TCP</title>
</head>
<body>
    <h1>Test de Connexion TCP</h1>

    <!-- Formulaire pour entrer l'adresse IP et le port -->
    <form action="/tcp" method="post">
        <label for="ip">Adresse IP :</label>
        <input type="text" id="ip" name="ip" required>
        <label for="port">Port :</label>
        <input type="number" id="port" name="port" required>
        <button type="submit">Tester</button>
    </form>

    <?php if (!empty($tcpResult)): ?>
        <h2>Résultat de la connexion TCP :</h2>
        <pre><?php echo htmlspecialchars($tcpResult); ?></pre>
    <?php endif; ?>
</body>
</html>
