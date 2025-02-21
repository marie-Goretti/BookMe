<?php
session_start();

class Database {
    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "";
    private $dbname = "bookme1";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Échec de la connexion : " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($nom, $prenom, $email, $password) {
        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            return "Tous les champs sont requis.";
        }
    
        $sql = "SELECT * FROM utilisateur WHERE email_utilisateur = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return "Cet email est déjà utilisé.";
        }
    
        // Définir le rôle (admin si mot de passe par défaut)
        $role = ($password === "admin123") ? "admin" : "user";
    
        $sql = "INSERT INTO utilisateur (nom__utilisateur, prenom_utilisateur, email_utilisateur, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $nom, $prenom, $email, $password, $role);
    
        if ($stmt->execute()) {
            header("Location: connexion.php");
            exit();
        } else {
            return "Erreur lors de l'inscription.";
        }
    }
    
}

$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo $user->register(trim($_POST['nom']), trim($_POST['prenom']), trim($_POST['email']), trim($_POST['password']));
}

$db->closeConnection();
?>




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
            <form action="inscription" method="POST">
                <h1>BookMe</h1>
                <p><Inscrivez-vous></Inscrivez-vous></p>
                <div class="D1">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nom" placeholder="entrez votre nom">
                </div>
                <div class="D1">
                    <i class="fas fa-user"></i>
                    <input type="text" name="prenom" placeholder="entrez votre prenom">
                </div>
                <div class="D1">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="entrez votre email">
                </div>
                <div class="D1">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="entrez votre mot de passe">
                </div>
                <button type="submit" name="valider" class="bouton">S'enrégistrer</button>
                <div class="D3">
                     <p>Vous avez déjà compte?<a href="connexion.php">connectez vous</a></p>
                </div>
            </form>
        </div>
    </section>
</body>
</html>