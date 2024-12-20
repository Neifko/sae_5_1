
<link rel="stylesheet" href="/css/informations.css">

    

<link rel="stylesheet" href="/css/pages.css">
    <h2>Test de Connexion TCP</h2>


    <!-- Formulaire pour entrer l'adresse IP et le port -->
    <form action="/tcp" method="post">
        <label for="ip">Adresse IP ou URL ou Domaine :</label>
        <input type="text" id="ip" name="ip" placeholder="(ex : github.com)" required>
        <label for="port">Port :</label>
        <input type="number" id="port" name="port" placeholder="(ex : 22)" required>
        <button type="submit">Tester</button>
    </form>


    <!-- Hint Information, fixed on the right -->
    <label for="hint-click">
        <input type="checkbox" id="hint-click">
        <div class="hint">
            <span class="fa-info">i</span>
            <p>
                Le module permet de tester la connectivité TCP entre l'utilisateur et une adresse IP ou une URL spécifique en spécifiant un port cible.<br>
                <br>
                Fonctionnement :<br>
                - L'utilisateur entre une adresse IP ou une URL (par exemple, <code>www.example.com</code>) et un numéro de port (par exemple, <code>80</code> pour HTTP).<br>
                - Le module établit une connexion TCP avec l'adresse cible sur le port spécifié et vérifie si la connexion est possible.<br>
                - Le module retourne le résultat, indiquant si la connexion a réussi ou échoué en fonction de la disponibilité du port sur l'hôte cible.<br>
                <br>
                Étapes :<br>
                1. Saisie de l'adresse IP ou de l'URL et du port : L'utilisateur entre l'URL ou l'adresse IP (par exemple, <code>www.example.com</code>) et le numéro de port à tester (par exemple, <code>80</code>).<br>
                2. Vérification de la connectivité TCP : Le module tente de se connecter à l'adresse cible sur le port spécifié.<br>
                3. Affichage du résultat : Le module affiche un message indiquant si la connexion TCP a réussi ou échoué.<br>
                <br>
                Exemple :<br>
                - L'utilisateur entre l'URL <code>www.example.com</code> avec le port <code>80</code> pour tester une connexion HTTP.<br>
                <br>
                Note : Si la connexion est réussie, cela signifie que le port est ouvert et accessible sur l'adresse cible. Si la connexion échoue, un message d'erreur indiquant le problème sera affiché, par exemple si le port est fermé ou si l'adresse est injoignable.<br>
            </p>
        </div>
    </label>


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

