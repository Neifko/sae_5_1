<link rel="stylesheet" href="css/moduleSousReseau.css">
    <title>Calculateur VLSM</title>


<h1>Calculateur de sous-réseaux VLSM</h1>
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

<div class="result" id="result"></div>


<script src="/javascript/module_sousreseau.js"></script>

