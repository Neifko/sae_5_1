<link rel="stylesheet" href="/css/dashboard.css">

<div class="dashboard-container">
    <!-- Colonne de gauche -->
    <div class="left-column">
        <div class="dashboard-block profil">
            <h2>Vous êtes connecté</h2>
            <a href="/profile/<?= htmlspecialchars($user_id) ?>">
                <img src="./images/user-interface.png" alt="Image Profil">
                Profil
            </a>
        </div>

        <div class="dashboard-block flash-message">
            <?php if (isset($_SESSION['flash_message'])): ?>
                <?= htmlspecialchars($_SESSION['flash_message']['content']) ?>
                <?php unset($_SESSION['flash_message']); ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Colonne de droite -->
    <div class="right-column">
        <div class="modules-grid">
            <!-- Modules -->
            <div class="dashboard-block">
                <ul class="dashboard-links">
                    <li><a href="/module_traduction">Module de Traduction IPV4</a></li>
                </ul>
            </div>
            <div class="dashboard-block">
                <ul class="dashboard-links">
                    <li><a href="/module_sousreseau">Module sous réseau</a></li>
                </ul>
            </div>
            <div class="dashboard-block">
                <ul class="dashboard-links">
                    <li><a href="/ipv4">IPv4</a></li>
                </ul>
            </div>
            <div class="dashboard-block">
                <ul class="dashboard-links">
                    <li><a href="/ipv6">IPv6</a></li>
                </ul>
            </div>
            <div class="dashboard-block">
                <ul class="dashboard-links">
                    <li><a href="/scapy">Module Scapy</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
