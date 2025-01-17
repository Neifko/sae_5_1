<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Application Web Pédagogique</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="icon" type="image/png" href="/images/logoProcrastinateursTransparent.png">
</head>
<body>

<div class="header">
    <div>
        <img id="logoProcrastinateurs" src="/images/logoProcrastinateurs.png" alt="logo procrastinateurs">
    </div>
    <div>
        <h1>Application Web Pédagogique</h1>
    </div>
    <?php if (!isset($_SESSION['user']) && (!in_array($_SERVER['REQUEST_URI'], ['/login', '/register']))): ?>
    <div id="boutonconnexion">
        <button>
            <a href="./login">Se connecter</a>
            -
            <a href="./register">S'inscrire</a>
        </button>
    </div>
    <?php elseif ((in_array($_SERVER['REQUEST_URI'], ['/login', '/register']))): // si non connecté et sur la page login ?>
    <div id="boutonRetourHeader">
        <button>
            <a href="/">Retour</a>
        </button>
    </div>
    <?php elseif (!in_array($_SERVER['REQUEST_URI'], ['/dashboard', '/login', '/register', '/ping', '/tcp', '/network', '/hexdump', '/ethernet'])): // si connecte et page non login ?>
    <div id="boutonRetourHeader">
        <button>
            <a href="/">Retour</a>
        </button>
    </div>
    <?php elseif (in_array($_SERVER['REQUEST_URI'], ['/ping', '/tcp', '/network', '/hexdump', '/ethernet'])): ?>
    <div id="boutonRetourHeader">
        <button>
            <a href="/scapy">Retour</a>
        </button>
    </div>
    <?php endif; ?>
</div>
<hr>