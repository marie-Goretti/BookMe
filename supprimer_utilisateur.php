<?php
require_once "database.php";

if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];

    $db = new Database();
    $conn = $db->getConnection();

    // Supprimer d'abord les emprunts liés à l'utilisateur
    $sql_delete_emprunts = "DELETE FROM emprunter WHERE id_utilisateur = ?";
    $stmt = $conn->prepare($sql_delete_emprunts);
    $stmt->bind_param("i", $id_utilisateur);
    $stmt->execute();
    $stmt->close();

    // Ensuite, supprimer l'utilisateur
    $sql_delete_user = "DELETE FROM utilisateur WHERE id_utilisateur = ?";
    $stmt = $conn->prepare($sql_delete_user);
    $stmt->bind_param("i", $id_utilisateur);
    $stmt->execute();
    $stmt->close();

    $db->closeConnection();

    header("Location: gestionutilisateur.php");
    exit();
} else {
    echo "ID utilisateur non fourni.";
}
?>
