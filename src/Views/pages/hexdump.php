<?php
$result = $_SESSION['result_hexdump'];
?>

<link rel="stylesheet" href="/css/informations.css">

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
    <!-- Hint Information, fixed on the right -->
    <label for="hint-click">
        <input type="checkbox" id="hint-click">
        <div class="hint">
            <span class="fa-info">i</span>
            <p>
                Le module Hexdump est un outil d'analyse et de création de paquets réseau utilisant la librairie Scapy. Ce module propose trois fonctionnalités principales : capturer, analyser et créer des paquets.<br>
                <br>
                Fonctionnalités :<br>
                <br>
                1. Capturer : Cette fonctionnalité utilise un sniffer basé sur Scapy pour capturer les paquets réseau entrants. Le module attend l'arrivée d'une requête ou d'un paquet spécifié, et dès qu'un paquet est détecté, il est intercepté et analysé. Cela permet à l'utilisateur de visualiser les paquets en temps réel et de comprendre leur structure interne.<br>
                <br>
                2. Analyser : Bien que cette fonctionnalité soit encore en développement, elle permettrait d'analyser le contenu des paquets capturés en affichant les données sous forme hexadécimale. Cela offre une vue détaillée des octets qui composent chaque paquet et permet de mieux comprendre les en-têtes et les données des paquets réseau.<br>
                <br>
                3. Créer : Cette fonctionnalité permet à l'utilisateur de créer un paquet réseau en spécifiant une adresse IP (source et destination). Le paquet ainsi créé peut être personnalisé avec différents paramètres, ce qui permet à l'utilisateur d'expérimenter avec la construction de paquets et de tester leur envoi vers une cible spécifique.<br>
                <br>
                Exemple d'utilisation :<br>
                - L'utilisateur peut capturer un paquet ICMP d'une adresse IP spécifique, analyser sa structure hexadécimale pour en comprendre les détails, puis créer et envoyer un paquet personnalisé vers un autre hôte réseau.<br>
                <br>
                Note : Ce module est particulièrement utile pour ceux qui souhaitent comprendre la composition des paquets réseau, expérimenter avec des outils de sniffer et de génération de paquets, et analyser en profondeur les échanges entre les hôtes d'un réseau.<br>
            </p>
        </div>
    </label>
    <div>
    <?php if (!empty($result)): ?>
        <h2>Résultats :</h2>
        <pre><?php echo htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT)); ?></pre>
    <?php endif; ?>
    </div>

    <?php if (!empty($error)): ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Erreur",
                text: <?php echo json_encode($error); ?>
            });
        </script>
    <?php endif; ?>
</div>

<div style="padding-bottom: 10%">
<?php
$_SESSION["result_hexdump"]  = null;
?>