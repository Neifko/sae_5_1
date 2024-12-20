# Manuel d'installation de l'Application Web Réseau
## Réalisé par Victor, Ostap, Alexis, Yassine et Xavier

## Table des Matières
1. [Introduction](#Introduction) 
2. [Installation sur Debian](#Installation-sur-Debian)
   * Prérequis
   * Etapes d'installation
   * Installation customisée
3. [Installation sur Windows](#Installation-sur-Windows)
   * Prérequis
   * Instructions
4. [Accès à l'Application](#Accès-à-l'Application)


# Introduction 

Cette application web propose plusieurs modules pour explorer les notions de réseau informatique, tels que :

* L'identification de la classe d’une adresse IPv4 et son écriture CIDR.
* La traduction entre les formats d’adresse IPv4 (hexadécimal, binaire, décimal pointé).
* La décomposition d'un réseau en sous-réseaux logiques (notation CIDR, technique VLSM).
* La composition d'une trame Ethernet avec la librairie Python Scapy.
* La simplification d’adresses IPv6.
* L'affichage et explication des paramètres d'une interface réseau.

# Installation sur Debian

### Prérequis
* Un système Debian à jour.
* Accès root pour les opérations d’installation. 
* Connexion internet active.

### Etapes d'installation

Un script Bash est fourni pour automatiser les étapes d'installation du projet (`script_installation.sh`).
 
<span style="color: #FF8000">ATTENTION : le script fais déjà tout le travail nécessaire du clonage du projet git (il faudra juste utiliser la manuel et le script d'installation).</span>

Ainsi, pour installer sur debian, il faut : 
1. Faire une copie du script d'installation **script_installation.sh** nommée **install_app.sh** :
```shell
cp script_installation.sh install_app.sh
```
2. Rendez le script exécutable si besoin :
```shell
chmod +x install_app.sh
```
3. Exécutez le script :
```shell
source install_app.sh
```
3. Deux possibilités vont s'ouvrir à  vous :
    * Entrez ```ALL``` pour tout installer (compatible également avec ```sudo ./install_app.sh```)
    * Entrez ```N``` (ou autre - ce que vous voulez) pour faire une installation customisée (voir categorie en dessous)
4. Suivre les instructions à l’écran, notamment pour saisir les mots de passe requis.

***Note : Il est préférable d'entrer ALL pour réussir l'installation***

### Instruction customisée

Si vous avez choisi une installation customisée, il faut entrer le nom des fonctions suivantes une à une et dans l'ordre indiqué.

* `update_system` : Mise à jour du système
* `install_web_server` : Installation du serveur web Apache et PHP
* `install_mariadb` : Installation de MariaDB
* `install_pip` : Installation de pip pour Python 3
* `clone_project` : Clonage du projet depuis GitHub
* `configure_database` : Configuration de la base de données
* `install_composer` : Installation de Composer
* `configure_apache` : Configuration d'Apache
* `configure_permissions` : Configuration des permissions des fichiers
* `install_scapy` : Installation de Scapy

les fonctions `install_mariadb` et  `configure_database` vont demander de saisir des mots de passe 


## Installation sur Windows

### Prérequis
* Installer XAMPP.
* Installer Python 3.x avec pip et Scapy et de pouvoir executer winpcap, scapy, etc ... en admin(```pip install ...```).
* Cloner le projet GitHub.

### Instructions

1. Dans ce projet, nous utilisons Composer. Pour l'installation, plusieurs options sont disponibles :

    * Vous pouvez installer directement le programme via l'exécutable disponible à l'adresse suivante :
    https://getcomposer.org/Composer-Setup.exe.

    * Alternativement, vous pouvez l'installer via un terminal shell. Assurez-vous que Composer est ajouté au PATH et que le fichier `composer.phar` est présent dans la racine du projet, puis exécutez la commande suivante :
        ```
        composer install
        ```
        ```
        composer require realrashid/sweet-alert
        ```

2. Ensuite, il est nécessaire de configurer XAMPP :

    Modification du fichier de configuration `httpd.conf`
    Le fichier httpd.conf se trouve généralement à l'emplacement suivant :
    `C:\xampp\apache\conf` (ou un autre emplacement selon l'installation de XAMPP).

    Modifiez les lignes suivantes dans le `httpd.conf`:

    ```
    DocumentRoot "C:/xampp/htdocs"
    <Directory "C:/xampp/htdocs">
    ```
    Par :
    ```
    DocumentRoot "U:/sae_5_1/public"
    <Directory "U:/sae_5_1/public">
    ```
    (Remplacez U:/sae_5_1/public par l'emplacement où le dépôt Git a été cloné, si nécessaire).

    Modification du fichier `php.ini`
    Ouvrez le fichier `php.ini`, situé à l'emplacement suivant :
    `C:\xampp\php\php.ini`.

    Recherchez la ligne suivante :

    ```
    ;extension=openssl
    ```
    Et remplacez-la par :

    ```
    extension=openssl
    ```

3. Utilisez phpMyAdmin pour créer la base de données en importer le fichier  `script_bdd.sql` situé dans la racine du projet.


4. Il faut maintenant configurer le fichier `.env` avec vos paramètres de base de données :
Commencez par copier le fichier `.env.base`, puis renommez la copie en `.env`.

Si vous souhaitez modifier l'utilisateur de la base de données, vous devez :

* Créer l'utilisateur dans votre système de gestion de base de données.
* Mettre à jour les informations correspondantes dans le fichier .env (nom d'utilisateur, mot de passe, nom de la base, etc.).


5. Utilisez phpMyAdmin pour créer la base de données et importer `script_bdd.sql` qui se situe dans la racine du projet.

Accédez au projet via `localhost` en s'assurant que XAMPP tourne bien `Apache` et `MySQL`.


## Accès à l'Application

1. Sur Debian :
    Une fois l'application installée sans erreurs, vous pouvez y accéder en utilisant l'adresse IP de la machine Debian : 
    ```
    http://adresse_ip_machine_debian
    ```
    Pour connaître l'adresse IP de la machine, exécutez la commande suivante :
    ```shell
    ip a s
    ```

2. Sur Windows :
Assurez-vous que dans XAMPP, les services `Apache` et `MySQL` sont démarrés et fonctionnent correctement.
Ensuite, accédez à l'application via l'adresse suivante :

```
http://localhost  
```
