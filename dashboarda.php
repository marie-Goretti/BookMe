<?php
// Démarre une session PHP pour gérer les données de session utilisateur
session_start();

// Inclut le fichier de connexion à la base de données
require_once "database.php";

// Vérifie si l'utilisateur est connecté en vérifiant la présence de la clé 'user' dans la session
if (!isset($_SESSION['user'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    // Arrête l'exécution du script
    exit();
}

// Instancie la classe Database pour gérer la connexion à la base de données
$db = new Database();
// Obtient la connexion à la base de données
$conn = $db->getConnection();
// Récupère l'email de l'utilisateur connecté depuis la session
$email = $_SESSION['user'];

// Prépare une requête SQL pour récupérer le prénom de l'utilisateur connecté
$sql = "SELECT prenom_utilisateur FROM utilisateur WHERE email_utilisateur = ?";
// Prépare la requête SQL pour l'exécution
$stmt = $conn->prepare($sql);
// Lie le paramètre email à la requête SQL
$stmt->bind_param("s", $email);
// Exécute la requête SQL
$stmt->execute();
// Récupère le résultat de la requête
$result = $stmt->get_result();
// Récupère la première ligne du résultat sous forme de tableau associatif
$user = $result->fetch_assoc();
// Récupère le prénom de l'utilisateur ou utilise "Utilisateur" par défaut si aucun prénom n'est trouvé
$prenom = $user ? $user['prenom_utilisateur'] : "Utilisateur";

// Récupère la valeur de la recherche depuis l'URL (paramètre GET 'search') ou utilise une chaîne vide par défaut
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prépare une requête SQL pour récupérer les livres en fonction de la recherche
$sql = "SELECT livre.id_livre, livre.nom_livre, livre.resume_livre, livre.date_publication, livre.image_livre, 
        livre.isbn, categorie.libele_categorie, 
        auteur.nom_auteur, auteur.prenom_auteur, livre.statut
        FROM livre
        INNER JOIN auteur ON livre.id_auteur = auteur.id_auteur
        INNER JOIN categorie ON livre.id_categorie = categorie.id_categorie
        WHERE livre.nom_livre LIKE ? 
        OR auteur.nom_auteur LIKE ? 
        OR auteur.prenom_auteur LIKE ? 
        OR livre.isbn LIKE ?";

// Prépare la requête SQL pour l'exécution
$stmt = $conn->prepare($sql);
// Ajoute des wildcards (%) autour de la chaîne de recherche pour une recherche partielle
$search_param = "%$search%";
// Lie les paramètres de recherche à la requête SQL
$stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
// Exécute la requête SQL
$stmt->execute();
// Récupère le résultat de la requête
$result = $stmt->get_result();
// Récupère le nombre de lignes retournées par la requête
$num = $result->num_rows;

// Prépare une requête SQL pour récupérer la liste de tous les auteurs
$auteurs_sql = "SELECT * FROM auteur";
// Exécute la requête SQL et récupère le résultat
$auteurs_result = $conn->query($auteurs_sql);

// Prépare une requête SQL pour récupérer la liste de toutes les catégories
$categorie_sql = "SELECT * FROM categorie";
// Exécute la requête SQL et récupère le résultat
$categorie_result = $conn->query($categorie_sql);

// Ferme la connexion à la base de données
$db->closeConnection();
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* General styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

h1{
    color: white; 
}

h2, h3 {
    color: #333;
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

/* Book list container */
.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.livre-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.livre-item {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin: 1.5rem 0;
    overflow: hidden;
    display: flex;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.livre-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.livreimg {
    flex: 0 0 200px;
    padding: 1rem;
}

.livreimg img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 8px;
}

.livreinfos {
    flex: 1;
    padding: 1.5rem;
}

.livreinfos h3 {
    margin: 0 0 1rem 0;
    font-size: 1.4rem;
    color: #333;
}

.livreinfos p {
    margin: 0.7rem 0;
    color: #666;
    line-height: 1.6;
}

.livreinfos strong {
    color: #444;
    font-weight: 600;
}

/* Button styles */
.button {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.button button {
    background: #9d56a1;
    border: none;
    border-radius: 6px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.button button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(157, 86, 161, 0.2);
}

.button button a {
    color: white;
    text-decoration: none;
    padding: 8px 16px;
    display: block;
}

/* Footer */
.piedPage {
    background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
    color: white;
    text-align: center;
    padding: 2rem;
    margin-top: 4rem;
}

.piedPage p {
    margin: 0 0 1rem 0;
}

.piedPage a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
    transition: opacity 0.3s ease;
}

.piedPage a:hover {
    opacity: 0.8;
}

/* Responsive design */
@media (max-width: 1024px) {
    .container {
        padding: 0 1rem;
    }
    
    .livre-item {
        flex-direction: column;
    }
    
    .livreimg {
        flex: none;
        padding: 1rem 1rem 0;
    }
    
    .livreimg img {
        height: 200px;
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-wrap: wrap;
        padding: 1rem;
    }
    
    .navbar ul {
        order: 2;
        width: 100%;
        justify-content: center;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    
    .search-container {
        order: 1;
        margin: 1rem 0;
    }
    
    h2 {
        font-size: 2rem;
        margin-top: 7rem;
    }
    
    .button {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .navbar a {
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
    }
    
    .search-input {
        width: 150px;
    }
    
    .search-input:focus {
        width: 180px;
    }
    
    .livre-item {
        margin: 1rem 0;
    }
    
    .livreinfos {
        padding: 1rem;
    }
    
    .livreinfos h3 {
        font-size: 1.2rem;
    }
    
    .button {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .button button {
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
            <li><a href="dashboarda" class="active"><i class="fas fa-book"></i>Livres</a></li>
            <li><a href="categorieadmin.php"><i class="fas fa-list"></i>Catégories</a></li>
            <li><a href="auteuradmin.php"><i class="fas fa-person"></i>Auteurs</a></li>
            <li><a href="gestionutilisateur.php"><i class="fas fa-person"></i>Utilisateurs</a></li>
        </ul>
        <!-- Formulaire de recherche -->
        <form method="GET" action="">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Rechercher..." class="search-input">
            </div>
        </form> 
        <div>
        <button>
            <a href="deconnexion.php" style="color: white; text-decoration: none;">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </button>
        </div>
    </nav>
</header>

<!-- Affichage du prénom de l'utilisateur connecté -->
<h2>Bienvenue, Administrateur !!!</h2>

<div class="ajout">
    <button><a href="ajouterlivre.php">Ajouter un livre</a></button>
    <button><a href="historique_pret.php">Historique de prêts</a></button>
    <button><a href="historique_retour.php">Historique de retours</a></button>
</div>

<!-- Section contenant la liste des livres -->
<section class="container">

    <?php if ($num > 0): ?>
        <!-- Liste des livres -->
        <ul class='livre-list'>
            <?php while ($row = $result->fetch_assoc()): ?>
                <!-- Élément de la liste pour chaque livre -->
                <li class='livre-item'>
                    <!-- Image du livre -->
                    <div class='livreimg'>
                        <img src='<?php echo !empty($row["image_livre"]) ? $row["image_livre"] : "images/img3.jpeg"; ?>' alt='<?php echo htmlspecialchars($row["nom_livre"]); ?>'>
                    </div>
                    <!-- Informations sur le livre -->
                    <div class='livreinfos'>
                        <h3>Titre : <?php echo htmlspecialchars($row["nom_livre"]); ?></h3>
                        <p><strong>Résumé:</strong> <?php echo htmlspecialchars($row["resume_livre"]); ?></p>
                        <p><strong>Date de publication:</strong> <?php echo htmlspecialchars($row["date_publication"]); ?></p>
                        <p><strong>ISBN:</strong> <?php echo htmlspecialchars($row["isbn"]); ?></p>
                        <p class='categorie'><strong>Catégorie:</strong> <?php echo htmlspecialchars($row["libele_categorie"]); ?></p>
                        <p class='auteur'><strong>Auteur:</strong> <?php echo htmlspecialchars($row["nom_auteur"] . " " . $row["prenom_auteur"]); ?></p>
                        <!-- Ajout du statut du livre -->
                        <p><strong>Statut:</strong> <?php echo htmlspecialchars($row["statut"]); ?></p>

                        <div class="button">
                            <!-- Boutons Modifier, Supprimer et Détails -->
                            <button><a href='modifier.php?id=<?php echo $row["id_livre"]; ?>'>Modifier</a></button>
                            <button><a href='supprimer.php?id=<?php echo $row["id_livre"]; ?>' onclick='return confirm("Êtes-vous sûr de vouloir supprimer ce livre ?");'>Supprimer</a></button>

                            <!-- Bouton Retourner (uniquement si le livre est non disponible) -->
                            <?php if ($row["statut"] === 'non disponible'): ?>
                                <button><a href='retourner_livre.php?id=<?php echo $row["id_livre"]; ?>'>Retourner</a></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Aucun livre trouvé.</p>
    <?php endif; ?>
</section>

<footer class="piedPage">
    <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
    <div>
        <a href="/a-propos">À propos</a> | 
        <a href="/contact">Contactez-nous</a>
    </div>
</footer>
</body>
</html>