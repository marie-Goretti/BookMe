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

// Récupérer l'ID de l'utilisateur actuel
$sql = "SELECT id_utilisateur FROM utilisateur WHERE email_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$id_utilisateur = $user['id_utilisateur'];

// Vérifier si l'ID du livre est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Livre introuvable.";
    exit();
}

$id_livre = $_GET['id'];

// Récupérer les détails du livre
$sql = "SELECT livre.*, auteur.nom_auteur, auteur.prenom_auteur, categorie.libele_categorie 
        FROM livre
        INNER JOIN auteur ON livre.id_auteur = auteur.id_auteur
        INNER JOIN categorie ON livre.id_categorie = categorie.id_categorie
        WHERE livre.id_livre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_livre);
$stmt->execute();
$result = $stmt->get_result();
$livre = $result->fetch_assoc();

if (!$livre) {
    echo "Livre non trouvé.";
    exit();
}

// Vérifier si l'utilisateur a emprunté ce livre
$check_sql = "SELECT * FROM emprunter WHERE id_livre = ? AND id_utilisateur = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $id_livre, $id_utilisateur);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$est_emprunte_par_moi = ($check_result->num_rows > 0);

// Gestion de l'emprunt
if (isset($_GET['emprunter'])) {
    if ($livre['statut'] === 'disponible') {
        $insert_sql = "INSERT INTO emprunter (id_livre, id_utilisateur, date_emprunt) VALUES (?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $id_livre, $id_utilisateur);

        if ($insert_stmt->execute()) {
            $update_sql = "UPDATE livre SET statut = 'indisponible' WHERE id_livre = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $id_livre);
            $update_stmt->execute();

            header("Location: livre_details.php?id=$id_livre");
            exit();
        } else {
            echo "Erreur lors de l'emprunt.";
        }
    }
}

// Gestion du retour
if (isset($_GET['retour'])) {
    if ($est_emprunte_par_moi) {
        $delete_sql = "DELETE FROM emprunter WHERE id_livre = ? AND id_utilisateur = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $id_livre, $id_utilisateur);

        if ($delete_stmt->execute()) {
            $update_sql = "UPDATE livre SET statut = 'disponible' WHERE id_livre = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $id_livre);
            $update_stmt->execute();

            header("Location: livre_details.php?id=$id_livre");
            exit();
        } else {
            echo "Erreur lors du retour du livre.";
        }
    }
}

?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($livre['nom_livre']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.navbar {
    background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
    color: white;
    padding: 1rem;
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
}

.navbar h1 {
    margin: 0;
    font-size: 24px;
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
    transition: background 0.3s;
}

.navbar a:hover {
    background: rgba(255, 255, 255, 0.2);
}

.navbar a.active {
    background-color: rgba(255, 255, 255, 0.3); 
    border-radius: 5px;
    font-weight: bold; 
}

.navbar button {
    background-color: transparent;
    color: white;
    border: none;
    cursor: pointer;
    padding: 10px 15px;
    display: flex;
    align-items: center;
}

h1 {
    font-size: 2em;
    text-align: center; 
    margin-top: 5em; 
}

.details-livre {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-top: 5em;
}

.details-livre img {
    max-width: 300px;
    height: auto;
    border-radius: 4px;
    margin-bottom: 20px;
}

h2 {
    color: #333;
}

p {
    font-size: 16px;
    color: #555;
    margin: 10px 0;
}

button {
    background-color: #a176b4;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px 15px;
    cursor: pointer;
    margin-top: 20px;
    font-size: 16px;
    transition: background-color 0.3s;
}

button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

button:hover:not(:disabled) {
    background-color: rgb(158, 73, 194);
}

footer {
    text-align: center;
    padding: 1rem;
    background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
    color: white;
    position: relative;
    bottom: 0;
    max-width: 1500px;
    display: flex;
    flex-wrap: wrap;
    align-items: center; 
}

footer a {
    text-align: center; 
    align-items: center;
}

@media (max-width: 600px) {
    .livres {
        flex-direction: column;
        align-items: flex-start;
    }

    .livre img {
        margin-bottom: 10px;
    }

    .navbar .search-container {
        margin: 0;
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
        <div>
            <button>
                <a href="deconnexion.php" style="color: white; text-decoration: none;"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
            </button>
        </div>
    </nav>
</header>
<h1>Détails du Livre</h1>

<section class="details-livre">
    <img src="<?php echo htmlspecialchars($livre['image_livre']); ?>" alt="<?php echo htmlspecialchars($livre['nom_livre']); ?>">
    <h2><?php echo htmlspecialchars($livre['nom_livre']); ?></h2>
    <p><b>Auteur :</b> <?php echo htmlspecialchars($livre['prenom_auteur'] . " " . $livre['nom_auteur']); ?></p>
    <p><b>Catégorie :</b> <?php echo htmlspecialchars($livre['libele_categorie']); ?></p>
    <p><b>Date de publication :</b> <?php echo htmlspecialchars($livre['date_publication']); ?></p>
    <p><b>ISBN :</b> <?php echo htmlspecialchars($livre['isbn']); ?></p>
    <p><b>Résumé :</b> <?php echo nl2br(htmlspecialchars($livre['resume_livre'])); ?></p>
    <p><b>Statut :</b> <?php echo htmlspecialchars($livre['statut']); ?></p>

    <?php if ($livre['statut'] === 'disponible'): ?>
                    <!-- Livre disponible : bouton Emprunter actif pour tous -->
                    <button><a href="emprunter.php?id=<?php echo $livre['id_livre']; ?>">Emprunter</a></button>
                <?php elseif ($est_emprunte_par_moi): ?>
                    <!-- Livre emprunté par l'utilisateur actuel : bouton Emprunter grisé, bouton Retourner actif pour les admins -->
                    <button disabled>Retourner</button>
                <?php else: ?>
                    <!-- Livre emprunté par quelqu'un d'autre : boutons grisés -->
                    <button disabled>Emprunter</button>
                <?php endif; ?>
</section>

<footer>
    <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
</footer>
</body>
</html>
<?php
$db->closeConnection();
?>
