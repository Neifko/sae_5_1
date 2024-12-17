<div>
    <table>
        <tr><th>Id</th><th>Nom d'utilisateur</th><th>Supprimer</th></tr>
        <?php foreach ($users as $user): ?>

        <tr><td><?= $user['id'] ?></td><td><?= $user['username'] ?></td><td><a href="/delete-user/<?= $user['id'] ?>">Supprimer</a></td></tr>

        <?php endforeach; ?>
    </table>
    <div>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <?php echo htmlspecialchars($_SESSION['flash_message']['content']); ?>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>
    </div>
</div>
<a href="/dashboard">Retour</a>