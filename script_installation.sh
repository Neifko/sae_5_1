#!/bin/bash
##### testé sur 192.168.24.137 mais c'est nul  à tester sur 192.168.24.141

apt-get update
apt-get install apache2
apt-get install php

#####

apt-get install mariadb-server
masql_secure_installation
# lors de l'install faut faire ENTER, n, ENTER (passwd : 1234) 
mariadb -u root -p
CREATE DATABASE sae51;
GRANT ALL ON sae51.* TO 'sae51'@'localhost' IDENTIFIED BY '12345' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EXIT;

mysql -u sae51 -p 12345
mysql -u sae51 -p sae51 < script_bdd.sql

######
apt-get install python3-pip


# 1. Installation du projet GIT
echo "Clonage du projet depuis GitHub..."
apt-get install git
cd /var/www
git clone https://github.com/Neifko/sae_5_1
cd sae_5_1

# 2. Définir la variable d'environnement
echo "Définition de la variable d'environnement..."
cp .env.base .env

# Install composer

apt-get install composer 

#  Installation des dépendances avec Composer
echo "Installation des dépendances avec Composer..."
composer install



# 4. Configuration Apache

# 4.1 Création de la configuration du site web
echo "Création de la configuration du site web..."
cd /etc/apache2/sites-available/
cp 000-default.conf sae51.conf
sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/sae_5_1/public|' sae51.conf
sed -i '/<\/VirtualHost>/i <Directory /var/www/sae_5_1/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>' sae51.conf

# 4.2 Activer la configuration Apache
echo "Activation de la configuration Apache..."
a2dissite 000-default
a2ensite sae51

# 4.3 Activer le module rewrite
echo "Activation du module rewrite..."
a2enmod rewrite
systemctl restart apache2

# 5. Configuration des permissions des fichiers

########################## testé jusque ici 

echo "Configuration des permissions des fichiers..."
chown -R www-data:www-data /var/www/sae_5_1/

# 6. Installation de Scapy avec pip et pip
echo "Installation de Scapy avec pip..."
apt-get install python3-pip
# python3 -m pip install scapy
apt-get install python3-scapy  # vesrion : python3 -c "import scapy; print(scapy.__version__)"


# 7. Attribution des capacités réseau à Python
echo "Attribution des capacités réseau à Python..."
apt-get install libcap2-bin
python_bin="python$(python3 --version 2>&1 | awk '{print $2}' | cut -d. -f1,2)"
setcap cap_net_raw,cap_net_admin=eip $(which $python_bin)

# 8. Fin du script
echo "Installation et configuration terminées !"
