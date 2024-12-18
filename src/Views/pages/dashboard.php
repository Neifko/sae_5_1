<link rel="stylesheet" href="./css/dashboard.css">
<link rel="stylesheet" href="./css/global.css">

    <h2>Vous êtes connecté</h2>
    <ul>
        <li><a href="/logout">Se déconnecter</a> </li>
        <li><a href="/profile/<?= htmlspecialchars($user_id) ?>">Profil</a> </li>
        <li><a href="/list-users">Liste des utilisateurs</a> </li>
    </ul>

    <div>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <?php echo htmlspecialchars($_SESSION['flash_message']['content']); ?>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>
    </div>

    <ul>

        <li><a href="/module_traduction">Module de Traduction IPV4</a></li>
        <li><a href="/module_sousreseau">Module sous réseau</a> </li>
        <li><a href="/ipv4">IPv4</a> </li>
        <li><a href="/ipv6">IPv6</a> </li>
        <li><a href="/scapy">Module Scapy</a> </li>
    </ul>
