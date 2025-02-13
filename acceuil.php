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
    font-family: 'Poppins', 'Arial', sans-serif;
    line-height: 1.6;
    background-color: #f4f4f4;
    color: #333;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Header Styles */
.headElement {
    background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
}

.navbar h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar ul {
    list-style: none;
    display: flex;
    gap: 2rem;
}

.navbar ul li a {
    font-weight: 2em; 
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.navbar ul li a:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.authentification{
    display: flex; 
    flex-wrap: wrap; 
    margin: 0;
}

.navbar .search-container {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 25px;
    padding: 0.5rem 1rem;
}

.search-input {
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 20px;
    width: 250px;
    background: white;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(157, 86, 161, 0.3);
    width: 300px;
}

/* Button Styles */
button {
    background-color: #9d56a1;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

button a {
    color: white; 
    text-decoration: none; 
}

button:hover {
    background-color: #8a4b8e;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Hero Section */
.container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8rem 4rem 4rem;
    max-width: 1400px;
    margin: 0 auto;
    gap: 4rem;
}

.slg {
    flex: 1;
    max-width: 600px;
}

.slg h1 {
    font-size: 4rem;
    font-weight: 800;
    color: white;
    margin-bottom: 1.5rem;
    line-height: 1.2;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.slg p {
    font-size: 1.5rem;
    color: white;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.slg-img {
    flex: 1;
    max-width: 500px;
}

.slg-img img {
    width: 100%;
    height: auto;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.slg-img img:hover {
    transform: translateY(-10px);
}

/* Main Content */
h2 {
    font-size: 3rem;
    text-align: center;
    margin: 4rem 0;
    color: #1c0129;
    font-weight: 700;
}

.mainElement {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.livres {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.livres:hover {
    transform: translateY(-10px);
}

.livre {
    text-align: center;
    padding: 2rem;
}

.livre img {
    width: 100%;
    max-width: 200px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.livreInfos {
    padding: 1.5rem;
}

.livreInfos p {
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.livreInfos a {
    color: #9d56a1;
    text-decoration: none;
    font-weight: 600;
}

.livreInfos a:hover {
    text-decoration: underline;
}

.livreInfos button a {
    color: white; 
}


/* Footer */
.piedPage {
    background: radial-gradient(circle, #9d56a1, rgb(239, 112, 143), #fbb063);
    color: white;
    text-align: center;
    padding: 2rem;
    margin-top: 4rem;
}

.piedPage p {
    margin: 0 0 1rem 0;
}

.piedPage a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
    transition: opacity 0.3s ease;
}

.piedPage a:hover {
    opacity: 0.8;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .container {
        padding: 6rem 2rem 2rem;
    }

    .slg h1 {
        font-size: 3.5rem;
    }
}

@media (max-width: 992px) {
    .container {
        flex-direction: column;
        text-align: center;
        padding-top: 8rem;
    }

    .slg {
        max-width: 100%;
    }

    .slg-img {
        max-width: 400px;
    }

    .navbar {
        padding: 1rem;
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        gap: 1rem;
    }

    .navbar ul {
        flex-direction: column;
        width: 100%;
        text-align: center;
        gap: 0.5rem;
    }

    .navbar .search-container {
        width: 100%;
        margin: 1rem 0;
    }

    .search-input {
        width: 100%;
    }

    .slg h1 {
        font-size: 2.5rem;
    }

    h2 {
        font-size: 2rem;
    }

    .mainElement {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    .navbar h1 {
        font-size: 2rem;
    }

    .container {
        padding: 6rem 1rem 1rem;
    }

    .slg h1 {
        font-size: 2rem;
    }

    .slg p {
        font-size: 1.2rem;
    }

    .slg-img {
        max-width: 300px;
    }

    button {
        padding: 0.6rem 1.2rem;
    }

    .livres {
        border-radius: 12px;
    }

    .livre {
        padding: 1rem;
    }

    .livreInfos {
        padding: 1rem;
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
            <div class="authentification">
                <div><button style='margin-left: 15em;'><a href="connexion.php">Se connecter</a></button></div>
                <div><button style='margin-left: 15em;'><a href="inscription.php">S'inscrire</a></button></div>
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