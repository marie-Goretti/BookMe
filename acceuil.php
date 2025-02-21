<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Reset and base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    line-height: 1.6;
    background-color: #f4f4f4;
    color: #333;
}

/* Header Styles */
.headElement {
    background:radial-gradient(circle, #9d56a1,rgb(239, 112, 143), #fbb063);
    padding-bottom: 1rem;
}

.navbar {
    position: fixed; /* Navbar fixed */
    top: 0;
    left: 0;
    right: 0;
    background: radial-gradient(circle, #9d56a1,rgb(239, 112, 143), #fbb063);
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    padding: 1em;
    z-index: 10;
}

.navbar h1 {
    font-size: 2.5rem;
    font-weight: bold;
    color: white;
    margin-left: 35px; 
    padding: 5px; 
}

.navbar ul {
    list-style: none;
    display: flex;
    gap: 1.5rem;
}

.navbar ul li a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.navbar .search-container {
    display: flex;
    align-items: center;
    background: white; 
    border-radius: 2px white solid; 
    padding: 5px; 
}

.search-input {
    padding: 0.75rem;
    border: none;
    border-radius: 0.5rem;
}

/* Button Styles */
button {
    background-color: #9d56a1;
    color: white;
    border: none;
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #a176b4;
}

button a {
    color: white;
    text-decoration: none;
}

/* Hero Section */
.container {
    display: flex;
    flex-wrap: wrap; 
    align-items: center;
    padding: 6rem 1rem 2rem; /* Add top padding for fixed navbar */
    gap: 2rem;
}

.slg {
    text-align: center;
    margin-left: 1em;
    width: 45em; 
}

.slg h1 {
    font-size: 4.5em;
    font-weight: bold;
    color: #1c0129;
    text-align: left; 
    margin-left: 30px; 
}

.slg p {
    font-size: 1.5rem;
    color: #1c0129;
    margin-bottom: 20px; 
}

.slg-img{
    width: 40em; 
}

.slg-img img{
    width: 100%;
}

.slg button{
    background-color: #9d56a1;
    width: 90%; 
    color: white;
    border: none;
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Main Content */
h2 {
    font-size: 2.5rem;
    text-align: center;
    margin: 3rem 0;
    color: #1c0129;
}

.mainElement {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    padding: 1rem;
}

.livres {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.livre {
    text-align: center;
    padding: 1rem;
}

.livre img {
    width: 100%;
    max-width: 200px;
    height: auto;
    border-radius: 0.5rem;
}

/* Footer */
.piedPage {
    background: radial-gradient(circle, #ed9baf, #fbb063);
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
    .navbar {
        flex-direction: column;
        text-align: center;
    }

    .navbar ul {
        flex-direction: column;
        width: 100%;
    }

    .container {
        flex-direction: column;
        text-align: center;
    }

    .slg h1 {
        font-size: 2.5rem;
    }
}

@media (max-width: 480px) {
    .navbar h1 {
        font-size: 2rem;
    }

    .slg h1 {
        font-size: 2rem;
    }

    h2 {
        font-size: 1.8rem;
    }

    .livre img {
        max-width: 150px;
    }

    button {
        padding: 0.5rem 1rem;
    }
}
    </style>
</head>
<body>
    <header class="headElement">
        <nav class="navbar">
            <h1>BookMe</h1>
            <div>
                <ul>
                    <li><a href="#"><i class="fas fa-home"></i>home</a></li>
                    <li><a href="inscription.php"><i class="fas fa-book"></i>livres</a></li>
                    <li><a href="inscription.php"><i class="fas fa-list"></i>catégorie</a></li>
                </ul>
            </div>
            <div>
                <button style='margin-left: 15em;'><a href="connexion.php">Se connecter</a></button>
               <button style='margin-left: 15em;'><a href="inscription.php">S'inscrire</a></button>
            </div>
        </nav>
        <section class="container">
            <div class="slg">
                <h1>Des livres à porter de clic</h1>
                <p>Choisissez votre prochaine lecture en quelques clics</p>
                <button><a href="inscription.php">Commencez</a></button>
            </div>
            <div class="slg-img">
                <img src="images/img1.png" alt="livres">
            </div>
        </section>
    </header>

    <h2>Découvez notre sélection...</h2>

    <section class="mainElement">
        <div class="livres">
            <article class="livre">
                <img src="images/img2.jpeg" alt="" style='max-width: 200px; max-height: auto; margin-top: 5px;'>
            </article>
            <aside class="livreInfos">
                <p>Titre : Harry Potter et l'ordre du phoenix</p>
                <p>Auteur : J.K. Rowling</p>
                <p>resumé... <a href="inscription.php">Lire plus</a></p>
                <button><a href="inscription.php">Empruntez</a></button>
            </aside>
        </div>
        <div class="livres">
            <article class="livre">
                <img src="images/img3.jpeg" alt="" style='max-width: 200px; max-height: auto; margin-top: 5px;'>
            </article>
            <aside class="livreInfos">
                <p>Titre : Harrt Potter à lécole des sorciers</p>
                <p>Auteur : J.K. Rowling</p>
                <p>resumé... <a href="inscription.php">Lire plus</a></p>
                <button><a href="inscription.php">Empruntez</a></button>
            </aside>
        </div>
        <div class="livres">
            <article class="livre">
                <img src="images/img4.jpeg" alt="" style='max-width: 200px; max-height: auto; margin-top: 5px;'>
            </article>
            <aside class="livreInfos">
                <p>Titre : Harry Potter et la coupe de feu</p>
                <p>Auteur : J.K. Rowling</p>
                <p>resumé... <a href="inscription.php">Lire plus</a></p>
                <button><a href="inscription.php">Empruntez</a></button>
            </aside>
        </div>
        <div class="livres">
            <article class="livre">
                <img src="images/img5.jpeg" alt="" style='max-width: 200px; max-height: auto; margin-top: 5px; '>
            </article>
            <aside class="livreInfos">
                <p>Titre : Harry Potter et le prince de sang mêlé</p>
                <p>Auteur : J.K. Rowling </p>
                <p>resumé... <a href="inscription.php">Lire plus</a></p>
                <button><a href="inscription.php">Empruntez</a></button>
            </aside>
        </div>
    </section>

    <footer class="piedPage">
        <p>&copy; 2025 Bibliothèque. Tous droits réservés.</p>
        <a href="/a-propos">À propos</a> | 
        <a href="/contact">Contactez-nous</a>
    </footer>
</body>
</html>