<?php
// User.php
class User {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getUserInfo($email) {
        $sql = "SELECT prenom_utilisateur, id_utilisateur FROM utilisateur WHERE email_utilisateur = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>