<?php
session_start();
require_once "database.php";
require_once "class/User.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_utilisateur']) && isset($_POST['nouveau_statut'])) {
    $db = new Database();
    $conn = $db->getConnection();
    
    $userObj = new User($conn);
    
    $id_utilisateur = $_POST['id_utilisateur'];
    $nouveau_statut = $_POST['nouveau_statut'];
    
    if ($userObj->updateUserStatus($id_utilisateur, $nouveau_statut)) {
        $_SESSION['success'] = $nouveau_statut === 'actif' 
            ? "L'utilisateur a été débloqué avec succès."
            : "L'utilisateur a été bloqué avec succès.";
    } else {
        $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour du statut.";
    }
    
    $db->closeConnection();
    header("Location: gestionutilisateur.php");
    exit();
}

header("Location: gestionutilisateur.php");
exit();
?>