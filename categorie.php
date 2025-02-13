<?php
session_start();
require_once "database.php";
require_once "class/Category.php";

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$categoryObj = new Category($conn);

// Get all categories
$categories = $categoryObj->getAllCategories();

// Get selected category if any
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories - BookMe</title>
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

        .navbar button {
            border: none;
            background: transparent;
        }

        .categories-nav {
            margin-top: 80px;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            position: sticky;
            top: 80px;
            z-index: 900;
        }

        .category-btn {
            background: #9d56a1;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-btn:hover {
            background: rgb(141, 56, 146);
            transform: translateY(-2px);
        }

        .category-btn.active {
            background: rgb(141, 56, 146);
            box-shadow: 0 0 10px rgba(157, 86, 161, 0.3);
        }

        section {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .livres-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .livre {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .livre:hover {
            transform: translateY(-5px);
        }

        .livre img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .livre p {
            margin: 5px 0;
        }

        .livre a {
            color: #9d56a1;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        .livre a:hover {
            text-decoration: underline;
        }

        footer {
            background: radial-gradient(circle, #ed9baf, #fbb063);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .categories-nav {
                margin-top: 60px;
                padding: 10px;
            }

            .category-btn {
                padding: 8px 16px;
                font-size: 14px;
            }

            .livres-container {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .livre img {
                height: 200px;
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
                <li><a href="categorie.php" class="active"><i class="fas fa-list"></i> Catégorie</a></li>
                <li><a href="profil.php"><i class="fas fa-user"></i> Profil</a></li>
            </ul>
            <div>
                <button>
                    <a href="deconnexion.php" style="color: white; text-decoration: none;">
                        <i class="fas fa-sign-out-alt"></i>Déconnexion
                    </a>
                </button>
            </div>
        </nav>
    </header>

    <div class="categories-nav">
        <?php foreach ($categories as $category) : ?>
            <button class="category-btn <?php echo $selectedCategory == $category['id_categorie'] ? 'active' : ''; ?>"
                    onclick="window.location.href='?category=<?php echo $category['id_categorie']; ?>'">
                <?php echo htmlspecialchars($category['libele_categorie']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <section>
        <?php
        if ($selectedCategory) {
            $books = $categoryObj->getBooksByCategory($selectedCategory);
            $categoryName = '';
            foreach ($categories as $cat) {
                if ($cat['id_categorie'] == $selectedCategory) {
                    $categoryName = $cat['libele_categorie'];
                    break;
                }
            }
            ?>
            <h2><?php echo htmlspecialchars($categoryName); ?></h2>
            <div class="livres-container">
                <?php while ($book = $books->fetch_assoc()) : ?>
                    <div class="livre">
                        <img src="<?php echo htmlspecialchars($book['image_livre']); ?>" 
                             alt="<?php echo htmlspecialchars($book['nom_livre']); ?>">
                        <p><b><?php echo htmlspecialchars($book['nom_livre']); ?></b></p>
                        <p>Auteur : <?php echo htmlspecialchars($book['prenom_auteur'] . " " . $book['nom_auteur']); ?></p>
                        <a href="livre_details.php?id=<?php echo $book['id_livre']; ?>">Détails</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php } else { ?>
            <h2>Sélectionnez une catégorie</h2>
        <?php } ?>
    </section>

    <footer>
        <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
    </footer>
</body>
</html>

<?php
$db->closeConnection();
?>