<form action="" method="post">
    <label for="username">Nom d'utilisateur</label>
    <input type="text" id="username" name="username">

    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password">

    <div>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <?php echo htmlspecialchars($_SESSION['flash_message']['content']); ?>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>
    </div>

    <input type="submit" value="Créer un compte">
</form>