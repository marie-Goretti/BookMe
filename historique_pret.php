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

// Requête pour récupérer l'historique des prêts avec les informations détaillées
$sql = "SELECT 
            e.id_emprunt,
            u.nom__utilisateur,
            u.prenom_utilisateur,
            l.nom_livre,
            e.date_emprunt,
            e.date_retour
        FROM emprunter e
        INNER JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur
        INNER JOIN livre l ON e.id_livre = l.id_livre
        ORDER BY e.date_emprunt DESC";

$result = $conn->query($sql);
$db->closeConnection();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Prêts - BookMe</title>
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
    color: white; 
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

        /* Contenu principal */
        .main-content {
            padding: 6rem 2rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .main-content h1 {
            margin-top: 1em;
            color: #333;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            background: linear-gradient(45deg, #9d56a1, rgb(239, 112, 143));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Table des prêts */
        .loans-table {
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .loans-table th {
            background: linear-gradient(45deg, #9d56a1, rgb(239, 112, 143));
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .loans-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            color: #444;
        }

        .loans-table tr:last-child td {
            border-bottom: none;
        }

        .loans-table tr:hover {
            background-color: #f8f9fa;
        }

        /* Status badges */
        .status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
        }

        .status-returned {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-pending {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        /* Responsive design */
        @media (max-width: 1024px) {
            .main-content {
                padding: 5rem 1rem 1rem;
            }

            .loans-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
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

        /* Déconnexion button */
        .logout-btn {
            background: transparent;
            border: 1px solid white;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
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
        <h1>Historique des Prêts</h1>
        
        <table class="loans-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Livre</th>
                    <th>Date d'emprunt</th>
                    <th>Date de retour prévue</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nom__utilisateur'] . ' ' . $row['prenom_utilisateur']); ?></td>
                        <td><?php echo htmlspecialchars($row['nom_livre']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['date_emprunt'])); ?></td>
                        <td>
                            <?php 
                            if ($row['date_retour']) {
                                $dateRetour = strtotime($row['date_retour']);
                                $dateActuelle = strtotime(date('Y-m-d'));
                                $differenceJours = ($dateRetour - $dateActuelle) / 86400;

                                $dateRetourFormat = date('d/m/Y', $dateRetour);

                                if ($differenceJours < 0) {
                                    // Retard
                                    echo "<span style='color: red; font-weight: bold;'>$dateRetourFormat - En retard !</span>";
                                } elseif ($differenceJours == 0) {
                                    // À rendre aujourd'hui
                                    echo "<span style='color: red; font-weight: bold;'>$dateRetourFormat - À rendre aujourd'hui !</span>";
                                } elseif ($differenceJours > 0 && $differenceJours <= 3) {
                                    // Bientôt à rendre
                                    echo "<span style='color: orange; font-weight: bold;'>$dateRetourFormat - À rendre bientôt !</span>";
                                } else {
                                    // Date normale
                                    echo $dateRetourFormat;
                                }
                            } else {
                                echo 'Non retourné';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>