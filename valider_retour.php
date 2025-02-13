<?php
require_once "database.php";

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_livre'], $_POST['id_utilisateur'], $_POST['date_retour_v'])) {
    $id_livre = $_POST['id_livre'];
    $id_utilisateur = $_POST['id_utilisateur'];
    $date_retour_v = $_POST['date_retour_v'];

    // Récupérer la date d'emprunt avant de supprimer l'emprunt
    $sql = "SELECT date_emprunt FROM emprunter WHERE id_livre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_livre);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $emprunt = $result->fetch_assoc();
        $date_emprunt = $emprunt['date_emprunt'];

        // Insérer les informations dans historique_emprunt
        $sql = "INSERT INTO historique_emprunt (id_utilisateur, id_livre, date_emprunt, date_retour_v) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $id_utilisateur, $id_livre, $date_emprunt, $date_retour_v);

        if ($stmt->execute()) {
            // Supprimer l'emprunt de la table emprunter
            $sql = "DELETE FROM emprunter WHERE id_livre = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_livre);
            $stmt->execute();

            // Mettre à jour le statut du livre en "disponible"
            $sql = "UPDATE livre SET statut = 'disponible' WHERE id_livre = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_livre);
            $stmt->execute();

            header("Location: dashboarda.php");
        } else {
            echo "Erreur lors de l'ajout à l'historique.";
        }
    } else {
        echo "Erreur : Emprunt introuvable.";
    }
} else {
    echo "Données manquantes.";
}

$db->closeConnection();
?>
