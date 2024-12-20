<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/header.css">
</head>
<body>

<div class="header">
    <div>
        <img id="logoProcrastinateurs" src="./images/logoProcrastinateurs.png" alt="logo procrastinateurs">
    </div>
    <div>
        <h1>Application Web Pédagogique</h1>
    </div>
    <?php if (!preg_match('/^(\/|\/dashboard)$/', $_SERVER['REQUEST_URI'])): ?>
        <div id="boutonRetourHeader">
            <button>
                <a href="/">Retour</a>
            </button>
        </div>
    <?php endif; ?>
</div>
<hr>