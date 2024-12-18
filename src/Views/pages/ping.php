
    <h1>Ping avec Scapy</h1>

    <form action="/ping" method="post">
        <label for="host">Adresse IP ou URL :</label>
        <input type="text" id="host" name="host" placeholder="www.free.fr" required>
        <button type="submit">Ping</button>
    </form>
    <div>
        <a href="/scapy">Retour</a>
    </div>

    <?php if (!empty($pingResult)): ?>
        <h2>Résultat du ping :</h2>
        <pre><?php echo htmlspecialchars($pingResult); ?></pre>
    <?php endif; ?>

