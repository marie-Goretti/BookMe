<?php
session_start();
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_livre'], $_POST['id_utilisateur'], $_POST['date_emprunt'], $_POST['date_retour'])) {
    $db = new Database();
    $conn = $db->getConnection();

    $id_livre = $_POST['id_livre'];
    $id_utilisateur = $_POST['id_utilisateur'];
    $date_emprunt = $_POST['date_emprunt'];
    $date_retour = $_POST['date_retour'];

    // Vérifier si l'utilisateur a déjà emprunté ce livre
    $check_sql = "SELECT * FROM emprunter WHERE id_utilisateur = ? AND id_livre = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $id_utilisateur, $id_livre);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // L'utilisateur a déjà emprunté ce livre
        header("Location: dashboardu.php?error=already_borrowed");
        exit();
    } else {
        // Insérer dans la table emprunter
        $insert_sql = "INSERT INTO emprunter (id_utilisateur, id_livre, date_emprunt, date_retour) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iiss", $id_utilisateur, $id_livre, $date_emprunt, $date_retour);

        if ($insert_stmt->execute()) {
            // Mettre à jour le statut du livre
            $update_sql = "UPDATE livre SET statut = 'non disponible' WHERE id_livre = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $id_livre);
            $update_stmt->execute();

            header("Location: dashboardu.php?success=1");
            exit();
        } else {
            echo "Erreur lors de l'emprunt.";
        }
    }

    $db->closeConnection();
} else {
    header("Location: dashboardu.php");
    exit();
}
?>