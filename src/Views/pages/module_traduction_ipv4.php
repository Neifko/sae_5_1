<link rel="stylesheet" href="/css/pages.css">
<link rel="stylesheet" href="/css/informations.css">

<h2><?php echo htmlspecialchars($title); ?></h2>

<?php if ($step === 1): ?>
    <!-- Étape 1 : Entrée de l'adresse -->
    <form action="" method="POST">
        <label for="address">Adresse IPv4 à convertir :</label>
        <input type="text" name="address" id="address" required placeholder="Ex : 192.168.1.30"/>

        <!-- Champ pour indiquer l'étape actuelle -->
        <input type="hidden" name="step" value="1"/>

        <input type="submit" value="Détecter le format"/>
    </form>
<?php elseif ($step === 2): ?>
    <!-- Étape 2 : Choix du format de sortie -->
    <form action="" method="POST">
        <p> Format détecté : <strong><?php echo ucfirst($adresse_detectee); ?></strong><br>
        Ip demandée : <strong><?php echo ucfirst($address); ?></strong></p>

        <label for="choix_format">Choisissez le format de conversion :</label>
        <select name="choix_format" id="choix_format" required>
            <?php foreach ($formats_disponibles as $key => $label): ?>
                <option value="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($label); ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Champs cachés pour conserver les données et indiquer l'étape -->
        <input type="hidden" name="step" value="2"/>
        <input type="hidden" name="address" value="<?php echo htmlspecialchars($address); ?>"/>
        <input type="hidden" name="adresse_detectee" value="<?php echo htmlspecialchars($adresse_detectee); ?>"/>

        <input type="submit" value="Convertir"/>
    </form>

<?php elseif ($step === 3): ?>
    <!-- Résultat -->
    <?php if (!empty($resultat)): ?>
        <p id="centerVertical"><strong>Résultat pour l'adresse <?php echo htmlspecialchars($address); ?>
                :<br></strong> <?php echo htmlspecialchars($resultat); ?></p>
    <?php endif; ?>
<?php endif; ?>


</div>
    <!-- Hint Information, fixed on the right -->
    <label for="hint-click">
        <input type="checkbox" id="hint-click">
        <div class="hint">
            <span class="fa-info">i</span>
            <p>
                Le module permet de convertir une adresse IPv4 entre plusieurs formats : décimal pointé, hexadécimal et binaire.<br>
                
                Fonctionnement :<br>
                - L'utilisateur entre une adresse IPv4 au format décimal pointé.<br>
                - Le module effectue la conversion vers le format hexadécimal et/ou binaire selon le choix de l'utilisateur.<br>
                - Il est également possible de convertir une adresse en hexadécimal ou binaire en adresse décimale pointée.<br>
                - Le module vérifie la validité des données avant de procéder à la conversion.<br>
                
                Étapes :<br>
                1. **Saisie de l'adresse IPv4** : L'utilisateur entre l'adresse en format décimal pointé.<br>
                2. **Choix du format de sortie** : L'utilisateur sélectionne le format(s) de conversion (hexadécimal et/ou binaire).<br>
                3. **Conversion** : Le module effectue la conversion dans le ou les formats choisis.<br>
                4. **Affichage des résultats** : Le module affiche l'adresse convertie dans les formats sélectionnés.<br>
                
                Exemple :<br>
                - Adresse : <code>192.168.1.30</code>, Conversion vers hexadécimal et binaire.<br>
                
                Détails :<br>
                - **Hexadécimal** : Adresse convertie en hexadécimal.<br>
                - **Binaire** : Adresse convertie en binaire.<br>
                
                **Note** : Le module assure la validité des formats avant toute conversion.<br>
            </p>
        </div>
    </label>

<?php if (isset($_SESSION['flash_message'])): ?>
    <script>
        Swal.fire({
            icon: "<?php echo htmlspecialchars($_SESSION['flash_message']['type']); ?>",
            type: "<?php echo htmlspecialchars($_SESSION['flash_message']['type']); ?>", // 'success', 'error', etc.
            text: "<?php echo htmlspecialchars($_SESSION['flash_message']['content']); ?>"
        })
    </script>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>


<div>

    <?php if ($step !== 1): ?>
    <p><a href="/module_traduction">Revenir en arrière</a></p>
    <?php endif; ?>

</div>

