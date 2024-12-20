<link rel="stylesheet" href="/css/pages.css">
    <h2>Test de Connexion TCP</h2>

    <!-- Formulaire pour entrer l'adresse IP et le port -->
    <form action="/tcp" method="post">
        <label for="ip">Adresse IP :</label>
        <input type="text" id="ip" name="ip" required>
        <label for="port">Port :</label>
        <input type="number" id="port" name="port" required>
        <button type="submit">Tester</button>
    </form>

    <?php if (!empty($error)): ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Erreur",
                text: <?php echo json_encode($error); ?>
            });
        </script>
    <?php endif; ?>

<div class="pingTcpResult">
    <?php if (!empty($tcpResult)): ?>
        <h2>Résultat de la connexion TCP :</h2>
        <pre><?php echo htmlspecialchars($tcpResult); ?></pre>
    <?php endif; ?>
</div>

