<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['id'])) {
    $id_livre = $_GET['id'];

    // Récupérer les informations de l'emprunt
    $sql = "SELECT emprunter.id_utilisateur, emprunter.date_emprunt, utilisateur.nom__utilisateur, livre.nom_livre 
            FROM emprunter 
            JOIN utilisateur ON emprunter.id_utilisateur = utilisateur.id_utilisateur 
            JOIN livre ON emprunter.id_livre = livre.id_livre 
            WHERE emprunter.id_livre = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_livre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $emprunt = $result->fetch_assoc();
    } else {
        echo "Aucune donnée trouvée.";
        exit();
    }
} else {
    echo "ID de livre manquant.";
    exit();
}

$db->closeConnection();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Retourner un Livre</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Assure que le corps remplit au moins la hauteur de la fenêtre */
        }

        header {
            background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
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
            transition: background 0.3s;
        }

        nav a:hover, nav a.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        nav button {
            background: transparent;
            border: none;
            cursor: pointer;
            color: white;
            font-size: 16px;
        }

        h2 {
            margin-top: 80px; /* Pour compenser la hauteur de la navbar */
            text-align: center;
            color: #333;
        }

        form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
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

        form a{
            color: #9d56a1;
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
    <header>
        <nav>
            <h1>BookMe</h1>
            <ul>
                <li><a href="dashboarda.php" class="active"><i class="fas fa-book"></i> Gestion livres</a></li>
                <li><a href="categorieadmin.php"><i class="fas fa-list"></i> Gestion catégories</a></li>
                <li><a href="auteuradmin.php"><i class="fas fa-person"></i> Gestion auteurs</a></li>
                <li><a href="historique.php"><i class="fas fa-history"></i> Historique</a></li>
            </ul>
            <div>
                <button><a href="deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></button>
            </div>
        </nav>
    </header>

    <h2>Retourner un Livre</h2>
    <form action="valider_retour.php" method="post">
        <input type="hidden" name="id_livre" value="<?php echo $id_livre; ?>">
        <input type="hidden" name="id_utilisateur" value="<?php echo htmlspecialchars($emprunt['id_utilisateur']); ?>">

        <label>Nom de l'utilisateur :</label>
        <input type="text" value="<?php echo htmlspecialchars($emprunt['nom__utilisateur']); ?>" readonly>

        <label>Nom du livre :</label>
        <input type="text" value="<?php echo htmlspecialchars($emprunt['nom_livre']); ?>" readonly>

        <label>Date d'emprunt :</label>
        <input type="text" value="<?php echo htmlspecialchars($emprunt['date_emprunt']); ?>" readonly>

        <label>Date de retour réelle :</label>
        <input type="date" name="date_retour_v" required>

        <button type="submit">Confirmer le retour</button>

        <a href="dashboarda.php">retour au tableau de bord</a>
    </form>

    <footer>
        <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
        <a href="/a-propos" style="color: white;">À propos</a> | 
        <a href="/contact" style="color: white;">Contactez-nous</a>
    </footer>
</body>
</html>