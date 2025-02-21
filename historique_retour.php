<?php
session_start();
require_once "database.php";

// Vérifie si l'administrateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Récupération des paramètres de recherche
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$date_search = isset($_GET['date_search']) ? trim($_GET['date_search']) : '';

// Construction de la requête SQL de base
$sql = "SELECT 
            h.id_historique,
            u.nom__utilisateur,
            u.prenom_utilisateur,
            l.nom_livre,
            h.date_emprunt,
            h.date_retour_v
        FROM historique_emprunt h
        INNER JOIN utilisateur u ON h.id_utilisateur = u.id_utilisateur
        INNER JOIN livre l ON h.id_livre = l.id_livre
        WHERE 1=1";

$params = array();
$types = "";

// Ajout des conditions de recherche si nécessaire
if (!empty($search)) {
    $sql .= " AND (u.nom__utilisateur LIKE ? OR u.prenom_utilisateur LIKE ? OR l.nom_livre LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "sss";
}

if (!empty($date_search)) {
    $sql .= " AND DATE(h.date_emprunt) = ?";
    $params[] = $date_search;
    $types .= "s";
}

$sql .= " ORDER BY h.date_retour_v DESC";

// Préparation et exécution de la requête
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$db->closeConnection();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Retours - BookMe</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Styles généraux */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f6f0f9;
            min-height: 100vh;
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

        /* Search Form */
        .search-form {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .search-group {
            flex: 1;
            min-width: 200px;
        }

        .search-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .search-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-group input:focus {
            outline: none;
            border-color: #9d56a1;
            box-shadow: 0 0 0 2px rgba(157, 86, 161, 0.2);
        }

        .search-button {
            background: linear-gradient(45deg, #9d56a1, rgb(239, 112, 143));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(157, 86, 161, 0.2);
        }

        /* Contenu principal */
        .main-content {
            padding: 6rem 2rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            background: linear-gradient(45deg, #9d56a1, rgb(239, 112, 143));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Table des retours */
        .returns-table {
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .returns-table th {
            background: linear-gradient(45deg, #9d56a1, rgb(239, 112, 143));
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .returns-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            color: #444;
        }

        .returns-table tr:last-child td {
            border-bottom: none;
        }

        .returns-table tr:hover {
            background-color: #f8f9fa;
        }

        /* Durée d'emprunt */
        .loan-duration {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
            background-color:rgb(240, 214, 241);
            color: #9d56a1;
        }

        /* No results message */
        .no-results {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-size: 1.1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Responsive design */
        @media (max-width: 1024px) {
            .main-content {
                padding: 5rem 1rem 1rem;
            }

            .returns-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .search-form {
                flex-direction: column;
            }

            .search-group {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 0.5rem;
            }

            .navbar ul {
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 0.5rem;
            }

            .navbar a {
                padding: 0.3rem 0.8rem;
                font-size: 0.9rem;
            }

            h1 {
                font-size: 2rem;
            }
        }

        /* Footer */
        .footer {
            background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
            color: white;
            text-align: center;
            padding: 1rem;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 2rem;
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

    <div class="main-content">
        <h1>Historique des Retours</h1>

        <!-- Formulaire de recherche -->
        <form method="GET" action="" class="search-form">
            <div class="search-group">
                <label for="search">Rechercher par nom, prénom ou livre</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="<?php echo htmlspecialchars($search); ?>" 
                    placeholder="Entrez votre recherche..."
                >
            </div>
            <div class="search-group">
                <label for="date_search">Rechercher par date d'emprunt</label>
                <input 
                    type="date" 
                    id="date_search" 
                    name="date_search" 
                    value="<?php echo htmlspecialchars($date_search); ?>"
                >
            </div>
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i>
                Rechercher
            </button>
        </form>
        
        <?php if ($result->num_rows > 0): ?>
        <table class="returns-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Livre</th>
                    <th>Date d'emprunt</th>
                    <th>Date de retour</th>
                    <th>Durée d'emprunt</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): 
                    $date_emprunt = new DateTime($row['date_emprunt']);
                    $date_retour = new DateTime($row['date_retour_v']);
                    $duree = $date_emprunt->diff($date_retour);
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nom__utilisateur'] . ' ' . $row['prenom_utilisateur']); ?></td>
                        <td><?php echo htmlspecialchars($row['nom_livre']); ?></td>
                        <td><?php echo $date_emprunt->format('d/m/Y'); ?></td>
                        <td><?php echo $date_retour->format('d/m/Y'); ?></td>
                        <td>
                            <span class="loan-duration">
                                <?php 
                                if ($duree->days == 0) {
                                    echo "Même jour";
                                } elseif ($duree->days == 1) {
                                    echo "1 jour";
                                } else {
                                    echo $duree->days . " jours";
                                }
                                ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-search" style="font-size: 2rem; color: #9d56a1; margin-bottom: 1rem;"></i>
                <p>Aucun résultat trouvé pour votre recherche.</p>
            </div>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>&copy; 2025 BookMe - Tous droits réservés</p>
    </footer>
</body>
</html>