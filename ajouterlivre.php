<?php
session_start();
require_once "database.php"; // Inclure le fichier de connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Connexion à la base de données
$db = new Database();
$conn = $db->getConnection();

// Récupérer les auteurs
$auteurs = [];
$sql = "SELECT id_auteur, nom_auteur FROM auteur";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $auteurs[] = $row;
}

// Récupérer les catégories
$categories = [];
$sql = "SELECT id_categorie, libele_categorie FROM categorie";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Fermeture de la connexion
$db->closeConnection();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de Livre</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

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


        form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
            margin-top: 14em;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }
        input, textarea, select {
            margin-top: 5px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            margin-top: 15px;
            background-color: #a176b4;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color:rgb(116, 13, 161);
        }
        a {
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            color: #a176b4;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header class="headElement">
    <nav class="navbar">
        <h1>BookMe</h1>
        <ul>
            <li><a href="dashboarda" class="active"><i class="fas fa-book"></i>Livres</a></li>
            <li><a href="categorieadmin.php"><i class="fas fa-list"></i>Catégories</a></li>
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

    
    <form method="post" action="traitement_ajout_livre.php" enctype="multipart/form-data">
        <h1>Ajouter un livre</h1>
        <label for="nom_livre">Titre du Livre:</label>
        <input type="text" id="nom_livre" name="nom_livre" required>

        <label for="resume_livre">Résumé:</label>
        <textarea id="resume_livre" name="resume_livre" required></textarea>

        <label for="date_publication">Date de Publication:</label>
        <input type="date" id="date_publication" name="date_publication">

        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn">

        <label for="nom_auteur">Nom de l'Auteur:</label>
        <select id="nom_auteur" name="nom_auteur" required>
            <option value="">Sélectionner un auteur</option>
            <?php foreach ($auteurs as $auteur) { ?>
                <option value="<?php echo htmlspecialchars($auteur['id_auteur']); ?>">
                    <?php echo htmlspecialchars($auteur['nom_auteur']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="libele_categorie">Catégorie:</label>
        <select id="libele_categorie" name="libele_categorie" required>
            <option value="">Sélectionner une catégorie</option>
            <?php foreach ($categories as $categorie) { ?>
                <option value="<?php echo htmlspecialchars($categorie['id_categorie']); ?>">
                    <?php echo htmlspecialchars($categorie['libele_categorie']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="image_livre">Image:</label>
        <input type="file" id="image_livre" name="image_livre" accept="image/*" required>

        <button type="submit">Enregistrer</button>
        <a href="dashboarda.php">Retour au tableau de bord</a>
    </form>
</body>
</html>