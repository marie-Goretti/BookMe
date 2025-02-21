<?php
session_start();
require_once "database.php";
require_once "class/User.php";
require_once "class/Book.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Initialize classes
$userObj = new User($conn);
$bookObj = new Book($conn);

// Get user information
$email = $_SESSION['user'];
$userInfo = $userObj->getUserInfo($email);
$prenom = $userInfo ? $userInfo['prenom_utilisateur'] : "Utilisateur";
$id_utilisateur = $userInfo['id_utilisateur'];

// Get books
$search = isset($_GET['search']) ? $_GET['search'] : '';
$result = $bookObj->searchBooks($search);

?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookMe</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
/* Reset and base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
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

.search-container input {
    padding: 10px;
    border-radius: 25px;
    border: none;
    background: white;
    width: 200px;
    font-size: 0.9rem;
    transition: width 0.3s ease;
}

.search-container input:focus {
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
/* Main Content */
h2 {
    color: #333;
    font-size: 2em;
    text-align: center;
    margin: 6rem 0 2rem;
    padding: 0 1rem;
}

.mainElement {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    justify-content: center;
}

/* Book Cards */
.livres {
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    width: 280px;
    transition: transform 0.2s ease;
}

.livres:hover {
    transform: translateY(-5px);
}

.livre {
    width: 100%;
    height: 300px;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem;
}

.livre img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 8px;
}

.livreInfos {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.livreInfos p {
    margin: 0.5rem 0;
    line-height: 1.4;
}

.livreInfos a {
    color: #9d56a1;
    text-decoration: none;
    font-weight: 500;
}

.livreInfos a:hover {
    text-decoration: underline;
}

.livreInfos button {
    margin-top: auto;
    width: 100%;
    padding: 0.8rem;
    border: none;
    border-radius: 5px;
    background: #9d56a1;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
}

.livreInfos button:hover {
    background: rgb(141, 56, 146);
}

.livreInfos button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.livreInfos button a {
    color: white;
    text-decoration: none;
    width: 100%;
    display: block;
}

/* Footer */
footer {
    background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
    color: white;
    padding: 1.5rem;
    margin-top: auto;
    text-align: center;
}

footer div {
    display: inline-block;
    margin: 0 1rem;
}

footer a {
    color: white;
    text-decoration: none;
    transition: opacity 0.3s ease;
}

footer a:hover {
    opacity: 0.8;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .mainElement {
        padding: 1.5rem;
    }
    
    .livres {
        width: 260px;
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-wrap: wrap;
        padding: 1rem;
    }

    .navbar ul {
        order: 3;
        width: 100%;
        justify-content: center;
        margin-top: 1rem;
    }

    .navbar .search-container {
        order: 2;
        margin: 1rem 0;
        max-width: none;
    }

    h2 {
        margin-top: 8rem;
    }
}

@media (max-width: 480px) {
    .mainElement {
        padding: 1rem;
    }
    
    .livres {
        width: 100%;
        max-width: 320px;
    }

    .navbar a {
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
    }

    h2 {
        font-size: 1.5em;
    }

    .livreInfos {
        padding: 1rem;
    }
}
    </style>
</head>

<body>
<header class="headElement">
    <nav class="navbar">
        <h1>BookMe</h1>
        <ul>
            <li><a href="dashboardu.php" class="active"><i class="fas fa-home"></i>Accueil</a></li>
            <li><a href="categorie.php"><i class="fas fa-list"></i> Catégorie</a></li>
            <li><a href="profil.php"><i class="fas fa-user"></i> Profil</a></li>
        </ul>
        <form method="GET" action="" class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($search); ?>" >
        </form>
        <div>
            <button>
                <a href="deconnexion.php" style="color: white; text-decoration: none;"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
            </button>
        </div>
    </nav>
</header>

<h2>Bienvenue, <?php echo htmlspecialchars($prenom); ?> !</h2>

<section class="mainElement">
<?php while ($livre = $result->fetch_assoc()) { ?>
<?php
    $check_result = $bookObj->checkBorrowStatus($livre['id_livre'], $id_utilisateur);
    $est_emprunte_par_moi = ($check_result->num_rows > 0);
    $livre_emprunte = ($livre['statut'] === 'emprunté');
?>
        <div class="livres">
            <div class="livre">
                <img src="<?php echo htmlspecialchars($livre['image_livre']); ?>" alt="<?php echo htmlspecialchars($livre['nom_livre']); ?>">
            </div>
            <div class="livreInfos">
                <p><b>Titre</b> : <?php echo htmlspecialchars($livre['nom_livre']); ?></p>
                <p><b>Auteur</b>: <?php echo htmlspecialchars($livre['prenom_auteur'] . " " . $livre['nom_auteur']); ?></p>
                <p><b>Statut</b> : <?php echo htmlspecialchars($livre['statut']); ?></p>
                <p><b>Résumé...</b> <a href="livre_details.php?id=<?php echo $livre['id_livre']; ?>">Lire plus</a></p>

                <?php if ($livre['statut'] === 'disponible'): ?>
                    <button><a href="emprunter.php?id=<?php echo $livre['id_livre']; ?>" style="color: white; text-decoration: none;">Emprunter</a></button>
                <?php elseif ($est_emprunte_par_moi): ?>
                    <button disabled>Retourner</button>
                <?php else: ?>
                    <button disabled>Emprunter</button>
                <?php endif; ?>
            </div>
        </div>
    <?php } ?>
</section>

<footer class="piedPage">
    <div><p>&copy; 2025 Bibliothèque. Tous droits réservés.</p></div>
    <div><a href="/a-propos">À propos</a> | </div>
    <div><a href="/contact">Contactez-nous</a></div>
</footer>
</body>
</html>
<?php
$db->closeConnection();
?>