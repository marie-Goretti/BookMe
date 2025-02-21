
Projet : BookMe 
Description
BookMe est une application web permettant aux utilisateurs de consulter et emprunter des livres numériques via une interface simple et intuitive. Elle offre des fonctionnalités telles que la recherche de livres, la consultation de leurs détails, et la gestion des emprunts. De plus, un espace administrateur permet de gérer la collection de livres et les utilisateurs, facilitant ainsi l'organisation et l'accès à une bibliothèque numérique moderne. Ce projet utilise HTML, CSS, Php et MySQL pour offrir une expérience fluide et efficace.
Prérequis
Avant d'installer le projet, assurez-vous d'avoir installé sur votre machine :
•	WAMP (https://www.wampserver.com/) 
WAMP inclut php (donc plus besoin d’installer Php), apache et MySQL
•	Navigateur web (Google Chrome, Firefox, etc.)
•	Editeur de texte pour lire le code (ex : Visual Studio Code)
Installation
1.	Cloner le projet depuis GitHub :
git clone https://github.com/marie-Goretti/BookMe.git
Ou télécharger directement le projet en ZIP et l’extraire.
2.	Copier le dossier du projet dans le répertoire suivant :
 C:/wamp64/www/bookme
3.	WAMP, puis lancer les services (Apache et MySQL se lancent automatiquement)
4.	Créer la base de données :
o	Ouvrir le navigateur et aller sur http://localhost/phpmyadmin
o	Créer une nouvelle base de données appelée bookme1
o	Importer le fichier bookme1.sql situé dans le dossier du projet.
5.	Vérifier les identifiants de la base de données dans le fichier database.php :
$host = '127.0.0.1'
$dbname = 'bookme1';
$user = 'root';
$password = '';
Exécution (Émulation)
1.	Ouvrir votre navigateur.
2.	Entrer l'adresse suivante : http://localhost/bookme/acceuil.php
3.	Naviguer sur le site et tester les fonctionnalités.
