
<div>
    <p style="color: white">Vous êtes <?= htmlspecialchars($username) ?></p>
</div>
<form action="" method="post">
    <label for="old-password">Ancien mot de passe</label>
    <input type="password" id="old-password" name="old-password">
    <label for="new-password">Nouveau mot de passe</label>
    <input type="password" id="new-password" name="new-password">

    <div>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <?php echo htmlspecialchars($_SESSION['flash_message']['content']); ?>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>
    </div>

    <input type="submit" value="Changer de mot de passe">
</form>
<div>
    <p>
        <a href="/logout">Se déconnecter</a>
    </p>
</div>
<div>
    <p>
        <a href="/dashboard">Retour</a>
    </p>
</div>