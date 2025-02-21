<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Récupérer les auteurs
$auteurs_sql = "SELECT * FROM auteur";
$auteurs_result = $conn->query($auteurs_sql);
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livres par Auteur</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
    padding: 0;
    margin: 0;
    background-color: #f6f0f9;
    font-family: Arial, sans-serif;
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

        h1 {
            text-align: center;
            color: #1c0129;
            margin-top: 20px;
        }

        .auteur {
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
            align-items: center; 
            display: inline-block;
            text-align: center;
            margin: 10px;
            transition: transform 0.3s;
        }

        .livre:hover {
            transform: scale(1.05);
        }

        img {
            width: 150px;
            height: 200px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .details-btn {
            display: block;
            margin-top: 5px;
            text-decoration: none;
            background: #9d56a1;;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .details-btn:hover {
            background:rgb(129, 46, 134);;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-input {
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
            <li><a href="categorieadmin.php"><i class="fas fa-list"></i>Catégories</a></li>
            <li><a href="auteuradmin.php" class="active"><i class="fas fa-person"></i>Auteurs</a></li>
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

<h1>Livres par Auteur</h1>

<button class="ajouter"><a href="ajouterauteur.php">Ajouter un auteur</a></button>

<?php while ($auteur = $auteurs_result->fetch_assoc()): ?>
    <div class="auteur">
        <h2><?php echo htmlspecialchars($auteur['prenom_auteur'] . ' ' . $auteur['nom_auteur']); ?></h2>
        <div>
            <?php
            $id_auteur = $auteur['id_auteur'];
            $livres_sql = "SELECT id_livre, nom_livre, image_livre FROM livre WHERE id_auteur = ?";
            $stmt = $conn->prepare($livres_sql);
            $stmt->bind_param("i", $id_auteur);
            $stmt->execute();
            $livres_result = $stmt->get_result();

            while ($livre = $livres_result->fetch_assoc()):
            ?>
                <div class="livre">
                    <img src="<?php echo htmlspecialchars($livre['image_livre']); ?>" alt="Image de <?php echo htmlspecialchars($livre['nom_livre']); ?>">
                    <p><?php echo htmlspecialchars($livre['nom_livre']); ?></p>
                    <a class="details-btn" href="details_livre.php?id=<?php echo $livre['id_livre']; ?>">Détails</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php endwhile; ?>

</body>
</html>

<?php $db->closeConnection(); ?>
