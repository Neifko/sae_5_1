<link rel="stylesheet" href="/css/pages.css">
<h2>Ping avec Scapy</h2>

<div>
    <form action="/ping" method="post">
        <label for="host">Adresse IP ou URL :</label>
        <input type="text" id="host" name="host" placeholder="www.free.fr" required>
        <button type="submit">Ping</button>
    </form>
</div>

    <?php if (!empty($error)): ?>
        <h2>Erreur</h2>
        <pre><?php echo htmlspecialchars($error); ?></pre>
        <script>
            Swal.fire({
                icon: "error",
                title: "Erreur",
                text: <?php echo json_encode($error); ?>
            });
        </script>

    <?php elseif (!empty($pingResult)): ?>
        <h2>Résultat du ping :</h2>
        <pre><?php echo htmlspecialchars($pingResult); ?></pre>
    <?php endif; ?>

<?php if (!empty($pingResult)): ?>
    <h2>Résultat du ping :</h2>
    <pre><?php echo htmlspecialchars($pingResult); ?></pre>
<?php endif; ?>
