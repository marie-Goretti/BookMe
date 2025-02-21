<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$email_utilisateur = $_SESSION['user'];

// Vérifier si un livre a été sélectionné
if (!isset($_GET['id'])) {
    header("Location: dashboardu.php");
    exit();
}

$id_livre = $_GET['id'];

// Récupérer les infos du livre
$sql = "SELECT livre.nom_livre, livre.image_livre, livre.resume_livre, auteur.nom_auteur, auteur.prenom_auteur 
        FROM livre 
        INNER JOIN auteur ON livre.id_auteur = auteur.id_auteur 
        WHERE livre.id_livre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_livre);
$stmt->execute();
$result = $stmt->get_result();
$livre = $result->fetch_assoc();

if (!$livre) {
    header("Location: dashboardu.php");
    exit();
}

// Récupérer les infos de l'utilisateur
$sql = "SELECT id_utilisateur, prenom_utilisateur FROM utilisateur WHERE email_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email_utilisateur);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: connexion.php");
    exit();
}

$id_utilisateur = $user['id_utilisateur'];
$prenom_utilisateur = $user['prenom_utilisateur'];
$date_emprunt = date('Y-m-d');
$date_retour = date('Y-m-d', strtotime("+14 days")); // Retour dans 14 jours
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emprunter un livre - BookMe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h1 {
            margin: 0;
            color: white; 
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        nav a.active {
            background-color: rgba(255, 255, 255, 0.3); 
            border-radius: 5px;
            font-weight: bold; 
        }

        button {
            background-color: transparent; /* Pas de couleur de fond */
            color: white;
            border: none; /* Enlever la bordure */
            cursor: pointer;
            padding: 10px 15px; /* Ajout de padding pour un meilleur espacement */
            display: flex; /* Pour aligner l'icône et le texte */
            align-items: center; /* Centrer verticalement */
        }

        button a {
            color: white;
            text-decoration: none;
            display: flex; /* Pour que l'icône et le texte soient alignés */
            align-items: center; /* Centrer verticalement */
        }

        section {
            margin-top: 80px; /* Pour compenser la hauteur de la navbar */
            padding: 20px;
            flex: 1;
            background-color: #f4f4f4;
        }


        .container {
            margin-top: 80px; /* Pour compenser la hauteur de la navbar */
            padding: 20px;
            max-width: 800px;
            margin: auto;
            margin-top: 7em;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .book-info {
            display: flex;
            margin-bottom: 20px;
        }

        .book-image {
            max-width: 150px;
            margin-right: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .book-details p {
            margin: 5px 0;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #9d56a1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        button[type="submit"]:hover {
            background-color: rgb(111, 44, 115);
        }

        footer {
            background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: auto; /* Permet au footer de se déplacer vers le bas */
            width: 100%;
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
        <div>
            <button>
                <a href="deconnexion.php" style="color: white; text-decoration: none;"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
            </button>
        </div>
    </nav>
</header>

<div class="container">
    <h2>Formulaire d'emprunt</h2>
    <div class="book-info">
        <img src="<?php echo htmlspecialchars($livre['image_livre']); ?>" alt="Couverture du livre" class="book-image">
        <div class="book-details">
            <p><strong>Titre :</strong> <?php echo htmlspecialchars($livre['nom_livre']); ?></p>
            <p><strong>Auteur :</strong> <?php echo htmlspecialchars($livre['prenom_auteur'] . " " . $livre['nom_auteur']); ?></p>
            <p><strong>Résumé :</strong> <?php echo htmlspecialchars($livre['resume_livre']); ?></p>
        </div>
    </div>

    <form method="POST" action="traitement_emprunt.php">
        <input type="hidden" name="id_livre" value="<?php echo $id_livre; ?>">
        <input type="hidden" name="id_utilisateur" value="<?php echo $id_utilisateur; ?>">
        
        <label>Nom de l'utilisateur :</label>
        <input type="text" value="<?php echo htmlspecialchars($prenom_utilisateur); ?>" disabled>

        <label>Date d'emprunt :</label>
        <input type="date" name="date_emprunt" value="<?php echo $date_emprunt; ?>" required>

        <label>Date de retour prévue :</label>
        <input type="date" name="date_retour" value="<?php echo $date_retour; ?>" required>

        <button type="submit">Valider l'emprunt</button>
        <?php 
        if (isset($_GET['success'])) {
            echo "<p>Livre emprunté avec succès !</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] === 'already_borrowed') {
            echo "<p>Vous avez déjà emprunté ce livre.</p>";
        }
        ?>
    </form>
</div>

<footer>
    <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
</footer>
</body>
</html>