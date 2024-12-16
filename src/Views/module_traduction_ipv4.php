<?php

use Victor\Sae51\Controllers\TraductionIPV4Controller;

$resultat = '';
$formats_disponibles = [];
$adresse_detectee = '';
$step = 1; // Étape par défaut : détecter le format d'entrée

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'] ?? '';
    $step = intval($_POST['step'] ?? 1); // Détecter l'étape actuelle (1 ou 2)

    $controller = new TraductionIPV4Controller();

    if ($step === 1) { // détection format d'entrée
        $adresse_detectee = $controller->detecter_format($address);
        if ($adresse_detectee !== "Adresse invalide") {
            $formats_disponibles = $controller->obtenir_formats_sortie($adresse_detectee);
            $step = 2;
        } else {
            $resultat = "Adresse invalide !";
        }
    } elseif ($step === 2) { // traduire selon le choix demandé
        $choix_format = $_POST['choix_format'] ?? '';
        $adresse_detectee = $_POST['adresse_detectee'];
        $resultat = $controller->script_traduction($address, $choix_format);
        $step = 3;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $title; ?></title>
</head>
<body>
<h1><?php echo $title; ?></h1>

<?php if ($step === 1): ?>
    <!-- Étape 1 : Entrée de l'adresse -->
    <form action="" method="POST">
        <label for="address">Adresse IPv4 à convertir :</label>
        <input type="text" name="address" id="address" required placeholder="Ex : 192.168.1.1"/>

        <!-- Champ pour indiquer l'étape actuelle -->
        <input type="hidden" name="step" value="1"/>

        <input type="submit" value="Détecter le format"/>
    </form>
<?php elseif ($step === 2): ?>
    <!-- Étape 2 : Choix du format de sortie -->
    <form action="" method="POST">
        <p>Format détecté : <strong><?php echo ucfirst($adresse_detectee); ?></strong></p>

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
        <p><strong>Résultat pour l'adresse <?php echo htmlspecialchars($address); ?>
                :<br></strong> <?php echo htmlspecialchars($resultat); ?></p>
    <?php endif; ?>
<?php endif; ?>

<footer>

    <?php if ($step === 1): ?>
    <p><a href="/">Revenir à l'accueil</a></p>
    <?php else: ?>
    <p><a href="/module_traduction">Revenir en arrière</a></p>
    <p><a href="/">Revenir à l'accueil</a></p>
    <?php endif; ?>

</footer>
</body>
</html>