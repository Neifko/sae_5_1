<link rel="stylesheet" href="/css/informations.css">

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
    <div class="centerVertical">Aucune donnée réseau disponible. Veuillez mettre à jour les informations.</div>
<?php endif; ?>


<!-- Hint Information, fixed on the right -->
<label for="hint-click">
        <input type="checkbox" id="hint-click">
        <div class="hint">
            <span class="fa-info">i</span>
            <p>
                Le module permet d'afficher les configurations réseau d'une machine, similaire à la commande <code>ip a</code> en ligne de commande.<br>
                <br>
                Fonctionnement :<br>
                - Le module récupère les informations réseau de la machine et affiche les différentes interfaces réseau avec leurs adresses IP, masques de sous-réseau, et autres informations pertinentes.<br>
                - Il permet ainsi de visualiser rapidement les configurations des interfaces réseau actives et de vérifier les paramètres de chaque connexion réseau.<br>
                <br>
                Étapes :<br>
                1. Récupération des informations réseau : Le module interroge la machine pour obtenir les détails des interfaces réseau.<br>
                2. Affichage des configurations : Le module affiche les adresses IP, masques de sous-réseau, et autres informations relatives à chaque interface réseau disponible.<br>
                <br>
                Exemple :<br>
                - Le module peut afficher une interface <code>eth0</code> avec une adresse IP <code>192.168.1.10</code>, un masque <code>/24</code>, et une interface <code>wlan0</code> avec une adresse IP <code>10.0.0.5</code>.<br>
                <br>
                Note : Ce module fournit un aperçu des configurations réseau actuelles de la machine, permettant de vérifier facilement les adresses IP assignées à chaque interface active.<br>
            </p>
        </div>
    </label>

