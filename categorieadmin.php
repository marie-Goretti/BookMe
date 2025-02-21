<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Récupérer les catégories
$categorie_sql = "SELECT * FROM categorie";
$categorie_result = $conn->query($categorie_sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livres par Catégorie</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: radial-gradient(circle, #9d56a1,rgb(239, 112, 143), #fbb063);
            color: white;
            padding: 15px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
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

        .categorie {
            background: #ffffff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center; /* Centrer le contenu */
            margin-bottom: 35px; 
            width: 90%;
            margin-left: 3em; 
            margin-top: 1em; 
        }

        .livre {
            display: inline-block;
            width: calc(25% - 20px);
            margin: 10px;
            text-align: center;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
            transition: transform 0.2s;
            height: 350px; /* Hauteur fixe pour uniformiser les livres */
            overflow: hidden; /* Pour éviter que le contenu déborde */
        }

        .livre:hover {
            transform: scale(1.05);
        }

        .livre img {
            max-width: 100%;
            height: 200px; /* Hauteur fixe pour les images */
            object-fit: cover; /* Pour garder le ratio d'aspect */
        }

        .details-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #9d56a1;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .details-btn:hover {
            background-color:rgb(136, 58, 141);
        }

        footer {
            background: radial-gradient(circle, #9d56a1,rgb(239, 112, 143), #fbb063);
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        @media (max-width: 800px) {
            .livre {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 500px) {
            .livre {
                width: 100%;
            }
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

<section>
    
    <h1>Livres par Catégorie</h1>

    <button class="ajouter"><a href="ajoutercategorie.php">Ajouter une catégorie</a></button>

    <?php while ($categorie = $categorie_result->fetch_assoc()): ?>
        <div class="categorie">
            <h2><?php echo htmlspecialchars($categorie['libele_categorie']); ?></h2>
            <div>
                <?php
                $id_categorie = $categorie['id_categorie'];
                $livres_sql = "SELECT livre.id_livre, livre.nom_livre, livre.image_livre, auteur.nom_auteur 
                               FROM livre 
                               JOIN auteur ON livre.id_auteur = auteur.id_auteur
                               WHERE livre.id_categorie = ?";
                $stmt = $conn->prepare($livres_sql);
                $stmt->bind_param("i", $id_categorie);
                $stmt->execute();
                $livres_result = $stmt->get_result();

                while ($livre = $livres_result->fetch_assoc()):
                ?>
                    <div class="livre">
                        <img src="<?php echo htmlspecialchars($livre['image_livre']); ?>" alt="Image de <?php echo htmlspecialchars($livre['nom_livre']); ?>">
                        <p><?php echo htmlspecialchars($livre['nom_livre']); ?></p>
                        <p>de <?php echo htmlspecialchars($livre['nom_auteur']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endwhile; ?>
</section>

<footer>
    <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
    <a href="/a-propos" style="color: white;">À propos</a> | 
    <a href="/contact" style="color: white;">Contactez-nous</a>
</footer>

<?php $db->closeConnection(); ?>
</body>
</html>