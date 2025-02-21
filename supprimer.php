<?php
session_start();
require_once "database.php"; // Inclure le fichier contenant la classe Database

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifier si l'ID du livre est passé en paramètre
if (!isset($_GET['id'])) {
    echo "Livre non trouvé.";
    exit();
}

$id_livre = $_GET['id'];

$db = new Database();
$conn = $db->getConnection();

// Préparer et exécuter la requête de suppression
$delete_sql = "DELETE FROM livre WHERE id_livre = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("i", $id_livre);

if ($stmt->execute()) {
    echo "Livre supprimé avec succès.";
    header("Location: dashboarda.php"); // Rediriger vers le tableau de bord
    exit();
} else {
    echo "Erreur lors de la suppression du livre.";
}

$db->closeConnection();
?>