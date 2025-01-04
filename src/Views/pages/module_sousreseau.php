<!--<link rel="stylesheet" href="css/moduleSousReseau.css">-->
<link rel="stylesheet" href="/css/informations.css">

<h2>Calculateur de sous-réseaux VLSM</h2>
<form id="vlsmForm" method="POST">
    <!-- Formulaire avec champ IP / CIDR côte à côte -->
    <div class="form-group">
        <label for="ip_address">Adresse IP du réseau :</label>
        <input type="text" id="ip_address" name="ip_address" value="192.168.1.0" required>
        <label for="cidr">/</label>
        <input type="text" id="cidr" name="cidr" value="24" required>
    </div>

    <!-- Champ pour le nombre de sous-réseaux -->
    <label for="nb_subnets">Nombre de sous-réseaux :</label>
    <input type="number" id="nb_subnets" name="nb_subnets" value="2" required>

    <button type="button" onclick="applySubnets()">Appliquer</button>

    <div id="subnetFormsContainer"></div>

    <div class="result" id="result"></div>
</form>

<label for="hint-click">
    <input type="checkbox" id="hint-click">
    <div class="hint">
        <span class="fa-info">i</span>
        <p style="height: 80%">
            Le module permet de diviser un réseau IP en sous-réseaux pour une gestion optimisée des adresses IP.<br>
            <br>
            Fonctionnement :<br>
            - L'utilisateur entre une adresse IP, un CIDR et le nombre de sous-réseaux à créer.<br>
            - Le module vérifie la validité des données et calcule les informations pour chaque sous-réseau :<br>
                - Nombre d'hôtes disponibles, adresse réseau, adresse de broadcast, plage d'adresses utilisables et masque de sous-réseau.<br>
            - Si les hôtes demandés dépassent la capacité, une alerte est donnée.<br>
            <br>
            Étapes :<br>
            1. Saisie des informations : Adresse IP, CIDR et nombre de sous-réseaux/hôtes.<br>
            2. Calcul de la capacité d'adressage : Vérification des sous-réseaux avec le nombre d'hôtes.<br>
            3. Validation des sous-réseaux : Vérification des sous-réseaux pour s'assurer de leur compatibilité.<br>
            4. Affichage des résultats : Détails des sous-réseaux créés, incluant les adresses et les hôtes.<br>
            <br>
            Exemple :<br>
            - IP : <code>192.168.1.0</code>, CIDR : <code>/24</code>, 3 sous-réseaux (50, 30, 10 machines).<br>
            - Calcul et ajustement des adresses et masques pour chaque sous-réseau.<br>
            <br>
            Détails :<br>
            - Adresse réseau : Calculée avec l'IP et le masque.<br>
            - Adresse de broadcast : Calculée à partir de l'adresse réseau.<br>
            - Plage utilisable : Entre l'adresse réseau et l'adresse de broadcast.<br>
            <br>
            Note : Le nombre total d'hôtes ne doit pas dépasser la capacité globale du réseau.<br>
        </p>
    </div>
</label>

<div class="result" id="result"></div>


<script src="/javascript/module_sousreseau.js"></script>

