<link rel="stylesheet" href="/css/pages.css">
<link rel="stylesheet" href="/css/informations.css">

<h2>Ping avec Scapy</h2>

<div>
    <form action="/ping" method="post">
        <label for="host">Adresse IP ou URL :</label>
        <input type="text" id="host" name="host" placeholder="www.free.fr" required>
        <button type="submit">Ping</button>
    </form>
</div>

    <!-- Hint Information, fixed on the right -->
    <label for="hint-click">
        <input type="checkbox" id="hint-click">
        <div class="hint">
            <span class="fa-info">i</span>
            <p>
                Le module permet de tester la connectivité réseau en envoyant une requête **ping** à une URL ou à une adresse IP cible.<br>
                
                Fonctionnement :<br>
                - L'utilisateur entre soit une URL (par exemple, <code>www.example.com</code>) ou une adresse IP (par exemple, <code>192.168.1.1</code>) pour tester la connectivité.<br>
                - Le module résout l'URL en une adresse IP si une URL est entrée, puis utilise la commande **ping** pour envoyer une requête ICMP à l'adresse cible.<br>
                - Le module attend une réponse pour vérifier si l'URL ou l'adresse IP cible est accessible.<br>
                
                Étapes :<br>
                1. **Saisie de l'URL ou de l'adresse IP** : L'utilisateur entre l'URL ou l'adresse IP à tester (par exemple, <code>www.example.com</code> ou <code>192.168.1.1</code>).<br>
                2. **Résolution de l'URL (si nécessaire)** : Si une URL est entrée, le module la résout en adresse IP.<br>
                3. **Envoi du ping** : Le module envoie une requête ping à l'adresse cible (qu'elle soit URL ou IP).<br>
                4. **Affichage du résultat** : Le module affiche le résultat du ping, indiquant si la cible est joignable ou non.<br>
                
                Exemple :<br>
                - L'utilisateur entre l'URL <code>www.example.com</code> ou l'adresse IP <code>192.168.1.1</code> pour tester la connectivité réseau.<br>
                
                **Note** : Le résultat du ping affiche le temps de réponse et indique si la communication a réussi ou échoué. Si l'URL ou l'adresse IP n'est pas joignable, un message d'erreur sera affiché.<br>
            </p>
        </div>
    </label>

<?php if (!empty($pingResult)): ?>
    <h2>Résultat du ping :</h2>
    <pre><?php echo htmlspecialchars($pingResult); ?></pre>
<?php endif; ?>
