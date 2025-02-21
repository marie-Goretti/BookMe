<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Livre non trouvé.";
    exit();
}

$id_livre = $_GET['id'];
$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT * FROM livre WHERE id_livre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_livre);
$stmt->execute();
$result = $stmt->get_result();
$livre = $result->fetch_assoc();

if (!$livre) {
    echo "Livre non trouvé.";
    exit();
}

$sql_auteurs = "SELECT id_auteur, nom_auteur, prenom_auteur FROM auteur";
$auteurs = $conn->query($sql_auteurs);

$sql_categories = "SELECT id_categorie, libele_categorie FROM categorie";
$categories = $conn->query($sql_categories);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_livre = $_POST['nom_livre'];
    $resume_livre = $_POST['resume_livre'];
    $date_publication = $_POST['date_publication'];
    $isbn = $_POST['isbn'];
    $id_auteur = $_POST['id_auteur'];
    $id_categorie = $_POST['id_categorie'];

    $image_livre = $livre['image_livre'];
    if (isset($_FILES['image_livre']) && $_FILES['image_livre']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $image_livre = $upload_dir . basename($_FILES['image_livre']['name']);
        move_uploaded_file($_FILES['image_livre']['tmp_name'], $image_livre);
    }

    $update_sql = "UPDATE livre SET nom_livre=?, resume_livre=?, date_publication=?, isbn=?, id_categorie=?, id_auteur=?, image_livre=? WHERE id_livre=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssiisi", $nom_livre, $resume_livre, $date_publication, $isbn, $id_categorie, $id_auteur, $image_livre, $id_livre);
    
    if ($update_stmt->execute()) {
        header("Location: dashboarda.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour du livre.";
    }
}
$db->closeConnection();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Livre</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        
/* Navbar */
.navbar {
    background: radial-gradient(circle, #9d56a1,rgb(239, 112, 143), #fbb063);
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
}

.navbar h1 {
    margin: 0;
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
    background-color: transparent; /* Pas de couleur de fond */
    color: white;
    border: none; /* Enlever la bordure */
    cursor: pointer;
    padding: 10px 15px; /* Ajout de padding pour un meilleur espacement */
    display: flex; /* Pour aligner l'icône et le texte */
    align-items: center; /* Centrer verticalement */
}

.navbar button a {
    color: white;
    text-decoration: none;
    display: flex; /* Pour que l'icône et le texte soient alignés */
    align-items: center; /* Centrer verticalement */
}
        h2 {
            text-align: center;
            color: #a176b4;
            margin: 20px 0;
            margin-top: 5em;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="file"] {
            margin-bottom: 15px;
        }
        button {
            background-color: #a176b4;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color:rgb(134, 29, 179);
        }
        a {
            display: inline-block;
            margin-top: 10px;
            text-align: center;
            color: #a176b4;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        
.piedPage {
    background: radial-gradient(circle, #9d56a1,rgb(239, 112, 143), #fbb063);
    color: #fff;
    text-align: center;
    padding: 20px;
    margin-top: 20px;
    border-top: 2px solid rgb(242, 142, 160);
}

.piedPage a {
    color: #007BFF;
    text-decoration: none;
    margin: 0 10px;
}

/* Media Queries */
@media (max-width: 600px) {
    .livre-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .livreimg {
        margin-right: 0;
        margin-bottom: 10px;
    }

    .navbar {
        flex-direction: column;
        align-items: flex-start;
    }
}
    </style>
</head>
<body>
<header class="headElement">
    <nav class="navbar">
        <h1>BookMe</h1>
        <ul>
            <li><a href="dashboarda" class="active"><i class="fas fa-book"></i> Gestion livres</a></li>
            <li><a href="categorieadmin.php"><i class="fas fa-list"></i> Gestion catégories</a></li>
            <li><a href="auteuradmin.php"><i class="fas fa-person"></i> Gestion auteurs</a></li>
            <li><a href="historique.php"><i class="fas fa-history"></i>Historique</a></li>
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
    <h2>Modifier le livre: <?php echo htmlspecialchars($livre['nom_livre']); ?></h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="nom_livre">Titre:</label>
        <input type="text" id="nom_livre" name="nom_livre" value="<?php echo htmlspecialchars($livre['nom_livre']); ?>" required>

        <label for="resume_livre">Résumé:</label>
        <textarea id="resume_livre" name="resume_livre" required><?php echo htmlspecialchars($livre['resume_livre']); ?></textarea>

        <label for="date_publication">Date de publication:</label>
        <input type="date" id="date_publication" name="date_publication" value="<?php echo htmlspecialchars($livre['date_publication']); ?>" required>

        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($livre['isbn']); ?>" required>

        <label for="id_auteur">Auteur:</label>
        <select id="id_auteur" name="id_auteur" required>
            <?php while ($auteur = $auteurs->fetch_assoc()) { ?>
                <option value="<?php echo $auteur['id_auteur']; ?>" <?php if ($livre['id_auteur'] == $auteur['id_auteur']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($auteur['nom_auteur'] . " " . $auteur['prenom_auteur']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="id_categorie">Catégorie:</label>
        <select id="id_categorie" name="id_categorie" required>
            <?php while ($categorie = $categories->fetch_assoc()) { ?>
                <option value="<?php echo $categorie['id_categorie']; ?>" <?php if ($livre['id_categorie'] == $categorie['id_categorie']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($categorie['libele_categorie']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="image_livre">Image:</label>
        <input type="file" id="image_livre" name="image_livre" accept="image/*">

        <button type="submit">Modifier</button>
        <a href="dashboarda.php">Retour au tableau de bord</a>
    </form>

    <footer class="piedPage">
    <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
    <div>
        <a href="/a-propos">À propos</a> | 
        <a href="/contact">Contactez-nous</a>
    </div>
</footer>
</body>
</html>