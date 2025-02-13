<?php
session_start();
require_once "database.php"; // Inclure le fichier de connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $libele_categorie = $_POST['libele_categorie'];

    // Connexion à la base de données
    $db = new Database();
    $conn = $db->getConnection();

    // Préparer l'insertion
    $sql = "INSERT INTO categorie (libele_categorie) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $libele_categorie);

    if ($stmt->execute()) {
        echo "Catégorie ajoutée avec succès!";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
    $db->closeConnection();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Catégorie</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
<style>
 
/* Navbar */
.navbar {
    background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
    color: white;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.navbar h1 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: bold;
}

.navbar ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 20px;
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.navbar a:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.navbar a.active {
    background-color: rgba(255, 255, 255, 0.3);
    font-weight: bold;
}

/* Search container */
.search-container {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 25px;
    padding: 5px 15px;
}

.search-input {
    padding: 10px;
    border-radius: 25px;
    border: none;
    background: white;
    width: 200px;
    font-size: 0.9rem;
    transition: width 0.3s ease;
}

.search-input:focus {
    width: 250px;
    outline: none;
}

.search-icon {
    margin-right: 10px;
    color: white;
}

.navbar button{
    border: none; 
    background: transparent; 
}

/* Welcome section */
h2 {
    margin-top: 5rem;
    text-align: center;
    font-size: 2.5rem;
    color: #333;
    padding: 2rem 0;
}

/* Add book button */
.ajout {
    display: flex; 
    flex-wrap: wrap; 
    max-width: 1000px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.ajout button {
    background: #9d56a1;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    max-width: 300px;
    transition: all 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
    margin-bottom: 2px; 
}

.ajout button a {
    text-decoration: none; 
    color: white; 
}

.ajout button:hover {
    background: rgb(141, 56, 146);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(157, 86, 161, 0.2);
}

/* Conteneur principal */

h2{
    margin-top: 2em; 
    text-align: center; 
    font-size: 3em;
    margin-top: 10em;
}

.container {
    padding: 5rem 1rem 1rem; /* Padding pour la navbar fixe */
    max-width: 800px;
    margin: auto;
    margin-top: 10em;
}
}

.ajout button{
    display: block;
    margin-top: 5px;
    text-decoration: none;
            background: #9d56a1;
            color: white;
            padding: 10px 15px;
            margin-left: 7em; 
            width: 85%;
            border-radius: 5px;
            transition: background 0.3s;
}

.ajout button:hover{
    background:rgb(141, 56, 146);;
}

.ajout button a{
    color: white;
    text-decoration: none; 
}

    
    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
        font-size: 28px;
    }

    /* Style général du formulaire */
    .styled-form {
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-top: 5em;
    }

    /* Style pour le groupe de champs */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
    }

    /* Style pour le label */
    .form-group label {
        font-weight: bold;
        color: #333;
        font-size: 16px;
    }

    /* Style pour le champ de saisie */
    .form-group input[type="text"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        background-color: #fff;
        transition: border-color 0.3s ease;
    }

    /* Effet de survol pour le champ de saisie */
    .form-group input[type="text"]:hover {
        border-color: #a176b4;
    }

    .form-group a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}


    /* Effet de focus pour le champ de saisie */
    .form-group input[type="text"]:focus {
        border-color: #a176b4;
        outline: none;
        box-shadow: 0 0 5px rgb(165, 28, 223);
    }

    /* Style pour le bouton de soumission */
    .submit-button {
        width: 100%;
        padding: 12px;
        background-color: #a176b4;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    /* Effet de survol pour le bouton */
    .submit-button:hover {
        background-color:rgb(164, 133, 178);
    }

    /* Effet de focus pour le bouton */
    .submit-button:focus {
        outline: none;
        box-shadow: 0 0 5px rgb(142, 61, 177);
    }
</style>
</head>
<body>

<header class="headElement">
    <nav class="navbar">
        <h1>BookMe</h1>
        <ul>
            <li><a href="dashboarda"><i class="fas fa-book"></i>Livres</a></li>
            <li><a href="categorieadmin.php" class="active"><i class="fas fa-list"></i>Catégories</a></li>
            <li><a href="auteuradmin.php"><i class="fas fa-person"></i>Auteurs</a></li>
            <li><a href="gestionutilisateur.php"><i class="fas fa-person"></i>Utilisateurs</a></li>
        </ul>
        <div>
        <button>
            <a href="deconnexion.php" style="color: white; text-decoration: none;">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </button>
        </div>
    </nav>
</header>
    
    
    <form method="post" action="" class="styled-form">
    <h1>Ajouter une Catégorie</h1>
    <div class="form-group">
        <label for="libele_categorie">Catégorie:</label>
        <input type="text" id="libele_categorie" name="libele_categorie" required>
    </div>
    <button type="submit" class="submit-button">Enregistrer</button>
    <a href="dashboarda.php">Retour au tableau de bord</a>
</form>
</body>
</html>