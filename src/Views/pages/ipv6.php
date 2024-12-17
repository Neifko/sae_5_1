<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Simplification et Extension IPv6</title>
    <link rel="stylesheet" href="/css/global.css">
    <style>
        h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            color: #06708E;
            margin-bottom: 20px;
            text-align: center;
        }

        #ipv6-form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            position: absolute;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%); 
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #06708E;
        }
        input, button {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border: 1px solid #CEDDDE;
            border-radius: 5px;
            font-size: 1rem;
            color: #124660;
        }

        input:focus, button:focus {
            outline: none;
            border-color: #03C490;
        }

        button {
            background-color: #03C490;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #01A98D;
        }
        #resultDiv.success {
            border-color: green;
            color: green;
        }

        #resultDiv.error {
            border-color: red;
            color: red;
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
        .form-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group input {
            width: 120px;
        }

        .form-group label {
            margin: 0;
        }
    </style>
</head>
<body>
    <h1>Simplification et Extension d'une adresse IPv6</h1>
    <form id="ipv6-form">
        <label for="ipv6">Entrez une adresse IPv6 :</label>
        <input type="text" id="ipv6" placeholder="ex : 2001:db8::ff00:42:8329" required>
        <button type="submit" id="simplifier-button">Simplifier</button>
        <button type="submit" id="etendre-button">Etendre</button>
    </form>
    
    <div id="resultDiv"></div>
    <div>
        <a href="/dashboard">Retour</a>
    </div>

    <script src="/javascript/classe_ipv6.js"></script>
</body>
</html>
