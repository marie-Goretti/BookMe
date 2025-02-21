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

// Recherche
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT id_utilisateur, nom__utilisateur, prenom_utilisateur, email_utilisateur FROM utilisateur WHERE role = 'user'";

if (!empty($search)) {
    $sql .= " AND (nom__utilisateur LIKE ? OR prenom_utilisateur LIKE ? )";
}

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bind_param('ss', $searchParam, $searchParam);
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
    <title>Utilisateurs - BookMe</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
       
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

h2 {
    color: radial-gradient(circle, #9d56a1, rgb(239, 112, 143));
}

        table {
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        table th {
            background: linear-gradient(45deg, #9d56a1, rgb(239, 112, 143));
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            color: #444;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table tr:hover {
            background-color: #f8f9fa;
        }

    </style>
</head>
<body>
<header class="headElement">
    <nav class="navbar">
        <h1>BookMe</h1>
        <ul>
            <li><a href="dashboarda"><i class="fas fa-book"></i>Livres</a></li>
            <li><a href="categorieadmin.php"><i class="fas fa-list"></i>Catégories</a></li>
            <li><a href="auteuradmin.php"><i class="fas fa-person"></i>Auteurs</a></li>
            <li><a href="gestionutilisateur.php" class="active"><i class="fas fa-person"></i>Utilisateurs</a></li>
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

<section>
    <h2>Gestion des utilisateurs</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nom__utilisateur']); ?></td>
                    <td><?php echo htmlspecialchars($row['prenom_utilisateur']); ?></td>
                    <td><?php echo htmlspecialchars($row['email_utilisateur']); ?></td>
                    <td>
                        <div>
                            <button>Boquer</button>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>
</body>
</html>
