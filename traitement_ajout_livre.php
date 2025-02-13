<?php
session_start();
require_once "database.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    $db = new Database();
    $conn = $db->getConnection();

    // Récupération des données du formulaire
    $nom_livre = $conn->real_escape_string($_POST['nom_livre']);
    $resume_livre = $conn->real_escape_string($_POST['resume_livre']);
    $date_publication = $conn->real_escape_string($_POST['date_publication']);
    $isbn = $conn->real_escape_string($_POST['isbn']);
    $id_auteur = (int)$_POST['nom_auteur'];
    $id_categorie = (int)$_POST['libele_categorie'];

    // Traitement de l'image
    $image_livre = null;
    if (isset($_FILES['image_livre']) && $_FILES['image_livre']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        
        // Créer le dossier uploads s'il n'existe pas
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Générer un nom unique pour l'image
        $file_extension = strtolower(pathinfo($_FILES['image_livre']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;

        // Vérifier le type de fichier
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed_types)) {
            $_SESSION['error'] = "Type de fichier non autorisé. Seuls les formats JPG, JPEG, PNG et GIF sont acceptés.";
            header("Location: ajout_livre.php");
            exit();
        }

        // Déplacer le fichier uploadé
        if (move_uploaded_file($_FILES['image_livre']['tmp_name'], $upload_path)) {
            $image_livre = $upload_path;
        } else {
            $_SESSION['error'] = "Erreur lors du téléchargement de l'image.";
            header("Location: ajout_livre.php");
            exit();
        }
    }

    // Préparation de la requête SQL
    $sql = "INSERT INTO livre (nom_livre, resume_livre, date_publication, isbn, id_categorie, id_auteur, image_livre, statut) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'disponible')";

    // Préparation et exécution de la requête
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssiis", 
            $nom_livre, 
            $resume_livre, 
            $date_publication, 
            $isbn, 
            $id_categorie, 
            $id_auteur, 
            $image_livre
        );

        if ($stmt->execute()) {
            $_SESSION['success'] = "Le livre a été ajouté avec succès.";
            header("Location: dashboarda.php");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du livre : " . $stmt->error;
            header("Location: ajouterlivre.php");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Erreur de préparation de la requête : " . $conn->error;
        header("Location: ajouterlivre.php");
        exit();
    }

    // Fermeture de la connexion
    $db->closeConnection();
} else {
    // Si le formulaire n'a pas été soumis, redirection vers la page d'ajout
    header("Location: ajouterlivre.php");
    exit();
}
?>