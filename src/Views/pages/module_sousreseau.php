
    <title>Calculateur VLSM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input, button {
            padding: 8px;
            margin: 10px 0;
        }
        /* Limiter la largeur des champs input */
        input, button {
            width: 250px;  /* Ajustez cette largeur selon vos besoins */
        }

        /* Formulaire avec champ IP / CIDR côte à côte */
        .form-group {
            display: flex;
            align-items: center;
            gap: 10px; /* Espacement entre les champs */
        }
        .form-group input {
            width: 120px; /* Réduit la largeur de chaque champ */
        }
        .form-group label {
            margin: 0;
        }

        .result {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .subnet-form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
    </style>

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

