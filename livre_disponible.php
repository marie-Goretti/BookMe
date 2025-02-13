<?php
session_start();
require_once "database.php";
require_once "class/user.php";
require_once "class/book.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$userObj = new User($conn);
$bookObj = new Book($conn);

$email = $_SESSION['user'];
$userInfo = $userObj->getUserInfo($email);

if ($userInfo['statut'] === 'bloque') {
    $_SESSION['error'] = "Votre compte a été bloqué. Veuillez contacter l'administrateur.";
    header("Location: compte_bloque.php");
    exit();
}

$prenom = $userInfo['prenom_utilisateur'];
$id_utilisateur = $userInfo['id_utilisateur'];

// Obtenir uniquement les livres disponibles
$result = $bookObj->getAvailableBooks();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Livres Disponibles</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Si tu as un fichier CSS externe -->
</head>
<body>
    <header class="headElement">
        <nav class="navbar">
            <h1>BookMe</h1>
            <ul>
                <li><a href="dashboardu.php"><i class="fas fa-home"></i></a></li>
                <li><a href="categorie.php"><i class="fas fa-list"></i> Catégorie</a></li>
                <li><a href="livre_disponible.php" class="active"><i class="fas fa-book"></i>Livres disponibles</a></li>
                <li><a href="profil.php"><i class="fas fa-user"></i> Profil</a></li>
            </ul>
            <div>
                <button>
                    <a href="deconnexion.php"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
                </button>
            </div>
        </nav>
    </header>

    <h2>Livres Disponibles</h2>

    <section class="mainElement">
        <?php while ($livre = $result->fetch_assoc()) { ?>
            <div class="livres">
                <div class="livre">
                    <img src="<?php echo htmlspecialchars($livre['image_livre']); ?>" alt="<?php echo htmlspecialchars($livre['nom_livre']); ?>">
                </div>
                <div class="livreInfos">
                    <p><b>Titre</b>: <?php echo htmlspecialchars($livre['nom_livre']); ?></p>
                    <p><b>Auteur</b>: <?php echo htmlspecialchars($livre['prenom_auteur'] . " " . $livre['nom_auteur']); ?></p>
                    <p><b>Résumé...</b> <a href="livre_details.php?id=<?php echo $livre['id_livre']; ?>">Lire plus</a></p>
                    <button><a href="emprunter.php?id=<?php echo $livre['id_livre']; ?>">Emprunter</a></button>
                </div>
            </div>
        <?php } ?>
    </section>

    <footer>
        <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
    </footer>
</body>
</html>

<?php $db->closeConnection(); ?>
