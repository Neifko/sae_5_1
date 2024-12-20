<div>
    <h1>Hexdump Tool</h1>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="/hexdump/process">
        <label for="action">Action :</label>
        <select name="action" id="action">
            <?php foreach ($actions as $key => $label): ?>
                <option value="<?php echo htmlspecialchars($key); ?>" 
                        <?php echo $key === $selected_action ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($label); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div id="additional-fields"></div>

        <button type="submit">Exécuter</button>
    </form>

    <?php if (!empty($result)): ?>
        <h2>Résultats :</h2>
        <pre><?php echo htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT)); ?></pre>
    <?php endif; ?>
    </div>
