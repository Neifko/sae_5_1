<div>
    <h2>Vous êtes connecté</h2>
    <ul>
        <li><a href="/logout">Se déconnecter</a> </li>
        <li><a href="/profile/<?= $user_id ?>">Profil</a> </li>
    </ul>

    <div>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <?php echo htmlspecialchars($_SESSION['flash_message']['content']); ?>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>
    </div>

    <ul>
        <li><a href="/list-users">Liste des utilisateurs</a> </li>
    </ul>
</div>