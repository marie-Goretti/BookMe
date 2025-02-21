<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Récupérer toutes les catégories
$sql = "SELECT id_categorie, libele_categorie FROM categorie";
$result = $conn->query($sql);
$categories = $result->fetch_all(MYSQLI_ASSOC);

?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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

.navbar button {
    border: none; 
    background: transparent; 
}

        section {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 8em;
        }

        h2 {
            color: #333;
            margin-top: 20px;
            text-align: center;
        }

        .livres-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .livre {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            width: 150px; /* Fixe la largeur des livres */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .livre img {
            width: 100%; /* Assure que l'image prend toute la largeur de l'élément livre */
            height: 200px; /* Fixe la hauteur des images */
            object-fit: cover; /* Maintient le ratio d'aspect tout en remplissant le conteneur */
            border-radius: 4px;
        }

        a {
            text-decoration: none;
            color: rgba(0, 0, 0, 0.98);
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        a {
            text-decoration: none;
            color: rgba(0, 0, 0, 0.98);
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
            <li><a href="dashboardu.php"><i class="fas fa-home"></i>Accueil</a></li>
            <li><a href="categorie.php" class="active"><i class="fas fa-list"></i> Catégorie</a></li>
            <li><a href="profil.php"><i class="fas fa-user"></i> Profil</a></li>
        </ul>
        <div>
            <button>
                <a href="deconnexion.php" style="color: white; text-decoration: none;"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
            </button>
        </div>
    </nav>
</header>

<section>
    <?php foreach ($categories as $categorie) : ?>
        <h2><?php echo htmlspecialchars($categorie['libele_categorie']); ?></h2>
        <div class="livres-container">
            <?php
            // Récupérer les livres de cette catégorie
            $sql = "SELECT livre.id_livre, livre.nom_livre, livre.image_livre, auteur.nom_auteur, auteur.prenom_auteur 
                    FROM livre
                    INNER JOIN auteur ON livre.id_auteur = auteur.id_auteur
                    WHERE livre.id_categorie = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $categorie['id_categorie']);
            $stmt->execute();
            $result_livres = $stmt->get_result();
            ?>
            
            <?php while ($livre = $result_livres->fetch_assoc()) : ?>
                <div class="livre">
                    <img src="<?php echo htmlspecialchars($livre['image_livre']); ?>" alt="<?php echo htmlspecialchars($livre['nom_livre']); ?>">
                    <p><b><?php echo htmlspecialchars($livre['nom_livre']); ?></b></p>
                    <p>Auteur : <?php echo htmlspecialchars($livre['prenom_auteur'] . " " . $livre['nom_auteur']); ?></p>
                    <a href="livre_details.php?id=<?php echo $livre['id_livre']; ?>">Détails</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endforeach; ?>
    <a href="dashboardu.php">Retour au tableau de bord</a>
</section>

<footer>
    <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
</footer>
</body>
</html>

<?php
$db->closeConnection();
?>
