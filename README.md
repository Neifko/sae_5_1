# SAE Semestre 5 : IN5SA01A - Développement avancé 
--
>Ce document présente le contenu du projet réalisé qui concerne une application web éducative sur le réseau. Le projet fait intervenir des modules réalisé avec la bibliothèque scapy de python.


## Somaire

1. [But](#But-de-l'application)
2. [Description](#Description)
3. [Utilisation](#Utilisation)


### But de l'application
Le but du projet est de produire une application web à vocation pédagogique sur les réseaux informatiques.  
Cette application permettra aux étudiants de mieux comprendre les concepts fondamentaux des réseaux à travers des 
modules interactifs et pratiques.

### Description
L'application est développée en suivant une architecture type MVC et est hebergée sur un serveur Apache.
Elle sera deployée en interne sur le réseau du département informatique de l'IUT.

Concernant les différents fichiers et répertoires on peut noter :
  * **[Public](public/)** : Dossier qui contient le sytle de l'application et la page d'accueil
  * **[Src](src/)** : Dossier qui contient le corps du projet et les fichiers relatifs au MVC, notemment pour les 
modules scapy
  * **[manuel_installation.md](manuel_installation.md)** : Manuel qui explique la démarche à suivre pour installer l'application  

### Utilisation

L'application propose différentes fonctionnalités en rapport avec les réseaux informatiques allant de la traduction 
d'ipv4 en binaire ou hexadécimale, jusqu'à la création d'une trame éthernet, en passant par des modules de ping.

> *Avant de pouvoir accéder aux différents modules, il vous suffit de vous connecter via le formulaire.  
> Si vous ne possédez pas de compte, il vous suffit d'en créer un à grâce au bouton s'inscrire, puis vous serez redirigé
> vers la page de connexion.*

En poursuivant votre visite, vous pourrez découvrir les modules présents sur le site.

Pour chaque module sur le site, vous trouverez une bulle info, située dans le coin supérieur droit. Cette "aide" vous 
permet de comprendre le fonctionnement du module choisi. Chaque module a été conçu pour être intuitif, ce qui permet 
une prise en main rapide.

