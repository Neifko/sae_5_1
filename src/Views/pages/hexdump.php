<div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const actionSelect = document.getElementById('action');
            const additionalFields = document.getElementById('additional-fields');
            
            // Template pour l'action 'capture' et 'sample'
            const fieldTemplates = {
                capture: `
                    <label for="interface">Interface réseau :</label>
                    <input type="text" name="interface" id="interface" required>
                `,
                sample: `
                    <label for="dst_ip">Adresse IP de destination :</label>
                    <input type="text" name="dst_ip" id="dst_ip" required>
                `
            };

            const updateFields = () => {
                const selectedAction = actionSelect.value;
                additionalFields.innerHTML = fieldTemplates[selectedAction] || '';
            };

            updateFields();
            actionSelect.addEventListener('change', updateFields);
        });
    </script>

    <h2>Hexdump Tool</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="/hexdump/process" enctype="multipart/form-data">
        <label for="action">Action :</label>
        <select name="action" id="action">
            <?php foreach ($actions as $key => $label): ?>
                <option value="<?php echo htmlspecialchars($key); ?>" 
                        <?php echo $key === $selected_action ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($label); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div id="additional-fields">
            <!-- Les champs spécifiques aux actions seront insérés ici -->
        </div>

        <button type="submit">Exécuter</button>
    </form>

    <?php if (!empty($result)): ?>
        <h2>Résultats :</h2>
        <pre><?php echo htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT)); ?></pre>
    <?php endif; ?>
    
    <div>
        <a href="/scapy">Retour</a>
    </div>
</div>
