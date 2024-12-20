<link rel="stylesheet" href="/css/pages.css">
<h2>Ping avec Scapy</h2>

<div>
    <form action="/ping" method="post">
        <label for="host">Adresse IP ou URL :</label>
        <input type="text" id="host" name="host" placeholder="www.free.fr" required>
        <button type="submit">Ping</button>
    </form>
</div>

<div class="pingTcpResult">
    <?php if (!empty($pingResult)): ?>
        <h2>Résultat du ping :</h2>
        <pre><?php echo htmlspecialchars($pingResult); ?></pre>
    <?php endif; ?>
</div>

