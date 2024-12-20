<h2>Informations Réseau</h2>

<!-- Formulaire pour mettre à jour le fichier JSON -->
<form action="/network/update" method="GET">
    <button type="submit">Mettre à jour les informations réseau</button>
</form>

<h2>Données réseau</h2>

<?php if (!empty($networkData)) : ?>
    <table>
        <thead>
        <tr>
            <th>Interface</th>
            <th>Adresse IP</th>
            <th>Adresse MAC</th>
            <th>Usage prévu</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($networkData as $interface => $details) : ?>
            <tr>
                <td><?= htmlspecialchars($interface) ?></td>
                <td><?= htmlspecialchars($details['Adresse IP']) ?></td>
                <td><?= htmlspecialchars($details['Adresse MAC']) ?></td>
                <td><?= htmlspecialchars($details['Usage prévu']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Aucune donnée réseau disponible. Veuillez mettre à jour les informations.</p>
<?php endif; ?>

<div>
    <a href="/scapy">Retour</a>
</div>
