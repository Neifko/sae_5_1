<div>
<h1>Outil Hexdump</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (isset($result)): ?>
    <h2>Résultat</h2>
    <pre><?= htmlspecialchars($result) ?></pre>
<?php endif; ?>

<form method="post" action="/hexdump/process">
    <label for="action">Sélectionnez une action :</label>
    <select name="action" id="action" onchange="toggleForm(this.value)" required>
        <option value="">-- Choisissez une option --</option>
        <?php foreach ($actions as $key => $label): ?>
            <option value="<?= $key ?>" <?= isset($selected_action) && $selected_action === $key ? 'selected' : '' ?>>
                <?= htmlspecialchars($label) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <!-- Formulaire dynamique -->
    <div id="capture" class="action-form" style="display: none;">
        <label for="interface">Interface réseau :</label>
        <input type="text" name="interface" id="interface">
    </div>

    <div id="analyze" class="action-form" style="display: none;">
        <label for="data">Données brutes :</label>
        <textarea name="data" id="data"></textarea>
    </div>

    <div id="sample" class="action-form" style="display: none;">
        <label for="dst_ip">Adresse IP :</label>
        <input type="text" name="dst_ip" id="dst_ip">
    </div>

    <div id="compare" class="action-form" style="display: none;">
        <label for="data1">Données 1 :</label>
        <textarea name="data1" id="data1"></textarea>
        <br>
        <label for="data2">Données 2 :</label>
        <textarea name="data2" id="data2"></textarea>
    </div>

    <br>
    <button type="submit">Exécuter</button>
</form>

<script>
    function toggleForm(action) {
        document.querySelectorAll('.action-form').forEach(form => form.style.display = 'none');
        if (action) {
            document.getElementById(action).style.display = 'block';
        }
    }

    // Si une action est déjà sélectionnée
    <?php if (isset($selected_action)): ?>
    toggleForm('<?= $selected_action ?>');
    <?php endif; ?>
</script>
</div>
