<?php
session_start(); // Démarre la session

// Détruire toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Redirigez l'utilisateur vers la page d'accueil ou la page de connexion
header('Location: acceuil.php'); // Ou redirigez vers la page de connexion : 'Location: connexion.php'
exit;
?>
