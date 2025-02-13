<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$email = $_SESSION['user'];

$sql = "SELECT * FROM utilisateur WHERE email_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$sql = "SELECT l.*, a.nom_auteur, a.prenom_auteur, e.date_emprunt, e.date_retour 
        FROM livre l 
        INNER JOIN emprunter e ON l.id_livre = e.id_livre 
        INNER JOIN auteur a ON l.id_auteur = a.id_auteur 
        WHERE e.id_utilisateur = ? AND e.date_retour >= CURRENT_DATE()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user['id_utilisateur']);
$stmt->execute();
$current_borrows = $stmt->get_result();

$sql = "SELECT l.*, a.nom_auteur, a.prenom_auteur, h.date_emprunt, h.date_retour_v 
        FROM livre l 
        INNER JOIN historique_emprunt h ON l.id_livre = h.id_livre 
        INNER JOIN auteur a ON l.id_auteur = a.id_auteur 
        WHERE h.id_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user['id_utilisateur']);
$stmt->execute();
$history = $stmt->get_result();

$sql = "SELECT COUNT(*) as total FROM historique_emprunt WHERE id_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user['id_utilisateur']);
$stmt->execute();
$total_borrows = $stmt->get_result()->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - BookMe</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
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

        .btn-deconnexion {
    background-color: transparent; /* Pas de couleur de fond */
    color: white;
    border: none; /* Enlever la bordure */
    cursor: pointer;
    padding: 10px 15px; /* Ajout de padding pour un meilleur espacement */
    display: flex; /* Pour aligner l'icône et le texte */
    align-items: center; /* Centrer verticalement */
}

.btn-deconnexion a {
    color: white;
    text-decoration: none;
    display: flex; /* Pour que l'icône et le texte soient alignés */
    align-items: center; /* Centrer verticalement */
}

        .profile-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 5em;
        }

        .profile-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center; /* Centrer le contenu */
            margin-bottom: 35px; 
        }

        .profile-info h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }

        .profile-info p {
            margin: 0.5rem 0;
            color: #666;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .number {
            font-size: 1.5rem;
            font-weight: bold;
            color:rgb(239, 112, 143);
        }

        .label {
            font-size: 0.9rem;
            color: #666;
        }

        .books-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgb(239, 112, 143);
        }

        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .book-item {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .book-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .book-author {
            font-size: 1rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .book-dates {
            font-size: 0.9rem;
            color: #666;
        }

        .book-dates span {
            display: block;
            margin-bottom: 0.5rem;
        }

        /* Footer */
        .piedPage {
            background: radial-gradient(circle, #9d56a1,rgb(239, 112, 143), #fbb063);
            color: white;
            text-align: center;
            padding: 2rem 1rem;
            margin-top: 4rem;
        }

        .piedPage a {
            color: white;
            text-decoration: none;
            margin: 0 0.5rem;
        }

        .piedPage a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar ul {
                flex-direction: column;
                gap: 0.5rem;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-deconnexion {
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>
<header class="headElement">
    <nav class="navbar">
        <h1>BookMe</h1>
        <ul>
            <li><a href="dashboardu.php"><i class="fas fa-home"></i>Accueil</a></li>
            <li><a href="categorie.php"><i class="fas fa-list"></i> Catégorie</a></li>
            <li><a href="profil.php" class="active"><i class="fas fa-user"></i> Profil</a></li>
        </ul>
        <div>
            <button>
                <a href="deconnexion.php" style="color: white; text-decoration: none;"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
            </button>
        </div>
    </nav>
</header>

    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['prenom_utilisateur'] . ' ' . $user['nom__utilisateur']); ?></h2>
                <p><?php echo htmlspecialchars($user['email_utilisateur']); ?></p>
                <div class="stats">
                    <div class="stat-item">
                        <div class="number"><?php echo $total_borrows; ?></div>
                        <div class="label">Livres retournés</div>
                    </div>
                    <div class="stat-item">
                        <div class="number"><?php echo $current_borrows->num_rows; ?></div>
                        <div class="label">Emprunts en cours</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="books-section">
            <h3 class="section-title">
                <i class="fas fa-book"></i>
                Emprunts en cours
            </h3>
            <div class="book-list">
            <?php while ($book = $current_borrows->fetch_assoc()): ?>
                <?php
                $dateRetour = strtotime($book['date_retour']);
                $dateActuelle = strtotime(date('Y-m-d'));
                $differenceJours = ($dateRetour - $dateActuelle) / 86400; // Différence en jours
                $alerte = '';

                if ($differenceJours == 0) {
                    $alerte = "<div style='color: red; font-weight: bold;'>Vous devez rendre ce livre aujourd'hui !</div>";
                } elseif ($differenceJours > 0 && $differenceJours <= 3) {
                    $alerte = "<div style='color: orange;'>Vous devez bientôt rendre ce livre (dans $differenceJours jours).</div>";
                }
                ?>
                <div class="book-item">
                    <div class="book-title"><?php echo htmlspecialchars($book['nom_livre']); ?></div>
                    <div class="book-author">par <?php echo htmlspecialchars($book['prenom_auteur'] . ' ' . $book['nom_auteur']); ?></div>
                    <div class="book-dates">
                        <span>Emprunté le: <?php echo date('d/m/Y', strtotime($book['date_emprunt'])); ?></span>
                        <span>À retourner le: <?php echo date('d/m/Y', $dateRetour); ?></span>
                    </div>
        <?php echo $alerte; ?>
    </div>
<?php endwhile; ?>

            </div>

            <h3 class="section-title" style="margin-top: 2rem;">
                <i class="fas fa-history"></i>
                Historique des emprunts
            </h3>
            <div class="book-list">
                <?php while ($book = $history->fetch_assoc()): ?>
                    <div class="book-item">
                        <div class="book-title"><?php echo htmlspecialchars($book['nom_livre']); ?></div>
                        <div class="book-author">par <?php echo htmlspecialchars($book['prenom_auteur'] . ' ' . $book['nom_auteur']); ?></div>
                        <div class="book-dates">
                            <span>Emprunté le: <?php echo date('d/m/Y', strtotime($book['date_emprunt'])); ?></span>
                            <span>Retourné le: <?php echo date('d/m/Y', strtotime($book['date_retour_v'])); ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <footer class="piedPage">
        <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
        <a href="/a-propos">À propos</a> | 
        <a href="/contact">Contactez-nous</a>
    </footer>
</body>
</html>

<?php $db->closeConnection(); ?>