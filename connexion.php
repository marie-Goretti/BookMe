<?php
session_start();
require_once "database.php";

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return "Tous les champs sont requis.";
        }

        // Vérifier les informations de connexion
        $sql = "SELECT * FROM utilisateur WHERE email_utilisateur = ? AND mot_de_passe = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user'] = $email;
            $_SESSION['role'] = $user['role'];

            // Rediriger en fonction du rôle
            if ($user['role'] === 'admin') {
                header("Location: dashboarda.php");
            } else {
                header("Location: dashboardu.php");
            }
            exit();
        } else {
            return "Email ou mot de passe incorrect.";
        }
    }
}


$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo $user->login(trim($_POST['email']), trim($_POST['password']));
}

$db->closeConnection();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface de connexion</title>
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
    min-height: 100vh;
    background: radial-gradient(circle, #9d56a1,rgb(239, 112, 143), #fbb063);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

/* Form Container */
section {
    width: 100%;
    max-width: 450px;
}

.D {
    background: white;
    padding: 2.5rem;
    border-radius: 1rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Form Header */
form h1 {
    color: #1c0129;
    font-size: 2.5rem;
    text-align: center;
    margin-bottom: 0.5rem;
}

form p {
    color: #666;
    text-align: center;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

/* Input Groups */
.D1 {
    position: relative;
    margin-bottom: 1.5rem;
}

.D1 i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a176b4;
    font-size: 1.2rem;
}

.D1 input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #eee;
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.D1 input:focus {
    outline: none;
    border-color: #a176b4;
}

.D1 input::placeholder {
    color: #999;
}

/* Button */
.bouton {
    width: 100%;
    padding: 1rem;
    background-color: #a176b4;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
    margin-top: 1em;
}

.bouton:hover {
    background-color: #9d56a1;
    transform: translateY(-2px);
}

.bouton:active {
    transform: translateY(0);
}

/* Login Link */
.D3 {
    text-align: center;
}

.D3 p {
    color: #666;
    margin: 0;
    font-size: 0.95rem;
}

.D3 a {
    color: #a176b4;
    text-decoration: none;
    font-weight: bold;
    margin-left: 0.5rem;
    transition: color 0.3s ease;
}

.D3 a:hover {
    color: #9d56a1;
    text-decoration: underline;
}

/* Error Messages */
.error-message {
    background-color: #ffe5e5;
    color: #d63031;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    text-align: center;
}

/* Success Messages */
.success-message {
    background-color: #e5ffe5;
    color: #27ae60;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    text-align: center;
}

/* Responsive Design */
@media (max-width: 480px) {
    .D {
        padding: 1.5rem;
    }

    form h1 {
        font-size: 2rem;
    }

    .D1 input {
        padding: 0.875rem 1rem 0.875rem 2.75rem;
    }

    .D1 i {
        font-size: 1.1rem;
    }

    .bouton {
        padding: 0.875rem;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.D {
    animation: fadeIn 0.5s ease-out;
}
    </style>
</head>
<body>
    <section>
        <div class="D">
                <form action="connexion.php" method="post">
            <h1>BookMe</h1>
            <div class="D1">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="entrez votre email" required>
            </div>
            <div class="D1">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="entrez votre mot de passe" required>
            </div>
            <div class="D2">
                <label><input type="checkbox">Se rappeler de moi</label>
                <a href="#">Mot de passe oublié?</a>
            </div>
            <button type="submit" name="valider" class="bouton">Se connecter</button>
            <div class="D3">
                <p>Vous n'avez pas de compte?<a href="inscription.php">Enregistrez-vous</a></p>
            </div>
        </form>
        </div>
    </section>
</body>
</html>
