<div>
    <table>
        <tr><th>Id</th><th>Nomd'utilisateur</th><th>Supprimer</th></tr>
        <?php foreach ($users as $user): ?>

        <tr><td><?= $user['id'] ?></td><td><?= $user['username'] ?></td><td><a href="/delete-user/<?= $user['id'] ?>">Supprimer</a></td></tr>

        <?php endforeach; ?>
    </table>
</div>