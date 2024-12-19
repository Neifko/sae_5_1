#!/bin/bash

# Fonction pour mettre à jour les paquets
update_system() {
    echo -e "\033[34mMise à jour du système...\033[0m"
    apt-get update || { echo "Mise à jour échouée"; exit 1; }
}

# Fonction pour installer Apache et PHP
install_web_server() {
    echo -e "\033[34mInstallation du serveur web Apache et PHP...\033[0m"
    apt-get install -y apache2 php php-cli php-fpm php-mysql php-pgsql php-sqlite3 php-bcmath php-bz2 php-intl php-gd php-imagick php-curl php-mbstring php-xml php-zip php-soap php-readline php-xdebug php-tokenizer php-json php-opcache php-redis php-ldap php-phpdbg php-dev
    a2enmod proxy_fcgi setenvif
    a2enconf php8.2-fpm
}

# Fonction pour installer MariaDB
install_mariadb() {
    echo -e "\033[34mInstallation de MariaDB...\033[0m"
    read -sp "Saisissez le mot de passe root pour l'installation de MariaDB : " root_mdp
    echo
    apt-get install -y mariadb-server || { echo "Installation de MariaDB échouée"; exit 1; }
    echo -e "\033[34mConfiguration sécurisée de MariaDB...\033[0m"
    mysql_secure_installation <<EOF

n
$root_mdp
$root_mdp
y
y
y
y
EOF
}

# Fonction pour installer pip
install_pip() {
    echo -e "\033[34mInstallation de pip pour Python 3...\033[0m"
    apt-get install -y python3-pip || { echo "Installation de pip échouée"; exit 1; }
}

# Fonction pour cloner le projet GitHub
clone_project() {
    echo -e "\033[34mClonage du projet depuis GitHub...\033[0m"
    apt-get install -y git || { echo "Installation de git échouée"; exit 1; }
    cd /var/www || exit
    git clone https://github.com/Neifko/sae_5_1 || { echo "Clonage échoué"; exit 1; }
    cd sae_5_1 || exit
    cp .env.base .env
}

# Fonction pour configurer la base de données
configure_database() {
    echo -e "\033[34mConfiguration de la base de données...\033[0m"
    read -sp "Saisissez votre mot de passe utilisateur pour l'installation de MariaDB : " user_mdp
    echo
    mariadb -u root -p$root_mdp <<EOF
CREATE DATABASE sae51;
GRANT ALL ON sae51.* TO 'sae51'@'localhost' IDENTIFIED BY '$user_mdp' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EXIT;
EOF
    mysql -u sae51 -p$user_mdp sae51 < script_bdd.sql || { echo "Erreur lors de la configuration de la base de données"; exit 1; }

    echo -e "\033[34mMise à jour du fichier .env...\033[0m"
    sed -i "s/^DB_USER=.*/DB_USER=sae51/" .env
    sed -i "s/^DB_PASS=.*/DB_PASS=$user_mdp/" .env
}

# Fonction pour installer Composer
install_composer() {
    echo -e "\033[34mInstallation de Composer...\033[0m"
    apt-get install -y composer
    composer install
}

# Fonction pour configurer Apache
configure_apache() {
    echo -e "\033[34mConfiguration d'Apache...\033[0m"
    cd /etc/apache2/sites-available/ || exit
    cp 000-default.conf sae51.conf
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/sae_5_1/public|' sae51.conf
    sed -i '/<\/VirtualHost>/i <Directory /var/www/sae_5_1/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>' sae51.conf
    a2dissite 000-default
    a2ensite sae51
    systemctl reload apache2
    a2enmod rewrite
    systemctl restart apache2
}

# Fonction pour configurer les permissions
configure_permissions() {
    echo -e "\033[34mConfiguration des permissions des fichiers...\033[0m"
    chown -R www-data:www-data /var/www/sae_5_1/
}

# Fonction pour installer Scapy
install_scapy() {
    echo -e "\033[34mInstallation de Scapy...\033[0m"
    apt-get install -y python3-scapy
    apt-get install -y libcap2-bin
    python_bin="python$(python3 --version 2>&1 | awk '{print $2}' | cut -d. -f1,2)"
    setcap cap_net_raw,cap_net_admin=eip $(which "$python_bin")
}

# Fonction principale pour exécuter toutes les étapes
main() {
    echo -e "\033[34mExécutez 'ALL' pour l'installation complète ou installez fonction par fonction :\033[0m"
    read -p "Votre choix : " choice

    if [[ "$choice" == "ALL" ]]; then
        echo -e "\033[34mExécution de toutes les fonctions...\033[0m"
        update_system
        install_web_server
        install_mariadb
        install_pip
        clone_project
        configure_database
        install_composer
        configure_apache
        configure_permissions
        install_scapy
        echo -e "\033[34mInstallation et configuration terminées !\033[0m"
    else
        echo -e "\033[34mAppelez les fonctions individuellement depuis le script.\033[0m"
    fi
}

main
