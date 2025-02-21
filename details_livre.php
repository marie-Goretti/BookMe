<?php
session_start();
require_once "database.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Récupérer l'ID du livre depuis l'URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
$id_livre = $_GET['id'];

// Connexion à la base de données
$db = new Database();
$conn = $db->getConnection();

// Récupérer les informations du livre
$sql_livre = "SELECT livre.id_livre, livre.nom_livre, livre.resume_livre, livre.date_publication, livre.image_livre, 
                     livre.isbn, categorie.libele_categorie, 
                     auteur.nom_auteur, auteur.prenom_auteur
              FROM livre
              INNER JOIN auteur ON livre.id_auteur = auteur.id_auteur
              INNER JOIN categorie ON livre.id_categorie = categorie.id_categorie
              WHERE livre.id_livre = ?";
$stmt_livre = $conn->prepare($sql_livre);
$stmt_livre->bind_param("i", $id_livre);
$stmt_livre->execute();
$result_livre = $stmt_livre->get_result();
$livre = $result_livre->fetch_assoc();

// Récupérer les emprunts du livre
$sql_historique = "SELECT utilisateur.prenom_utilisateur, utilisateur.nom__utilisateur, 
                        historique_emprunt.date_emprunt, historique_emprunt.date_retour_v
                 FROM historique_emprunt
                 INNER JOIN utilisateur ON historique_emprunt.id_utilisateur = utilisateur.id_utilisateur
                 WHERE historique_emprunt.id_livre = ?";
$sql_historique = $conn->prepare($sql_historique);
$sql_historique->bind_param("i", $id_livre);
$sql_historique->execute();
$result_historique = $sql_historique->get_result();

$db->closeConnection();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du livre</title>
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
            padding: 10px 20px;
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
            margin-top: 70px; /* Pour compenser la hauteur de la navbar */
            padding: 20px;
            flex: 1;
            background-color: #f4f4f4;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: rgb(242, 181, 196);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
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

        @media (max-width: 600px) {
            nav ul {
                flex-direction: column;
                align-items: flex-start;
            }

            nav ul li {
                margin: 5px 0;
            }
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
                <li><a href="historique.php"><i class="fas fa-history"></i>Historique</a></li>
            </ul>
            <div>
                <button><a href="deconnexion.php"><i class="fas fa-sign-out-alt"></i>Déconnexion</a></button>
            </div>
        </nav>
    </header>

    <section>
        <h1>Historique des retours</h1>

        <?php if ($result_historique->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($historique = $result_historique->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($historique['prenom_utilisateur'] . ' ' . $historique['nom__utilisateur']); ?></td>
                            <td><?php echo htmlspecialchars($historique['date_emprunt']); ?></td>
                            <td><?php echo htmlspecialchars($historique['date_retour_v'] ?? 'En cours'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun retour enregistré pour ce livre.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
        <a href="/a-propos">À propos</a> | 
        <a href="/contact">Contactez-nous</a>
    </footer>
</body>
</html>