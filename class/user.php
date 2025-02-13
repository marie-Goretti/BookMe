<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserInfo($email) {
        $stmt = $this->conn->prepare("SELECT id_utilisateur, prenom_utilisateur, nom__utilisateur, email_utilisateur, statut FROM utilisateur WHERE email_utilisateur = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function isBlocked($userId) {
        $stmt = $this->conn->prepare("SELECT statut FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user && $user['statut'] === 'bloque';
    }

    public function updateUserStatus($userId, $newStatus) {
        $stmt = $this->conn->prepare("UPDATE utilisateur SET statut = ? WHERE id_utilisateur = ?");
        $stmt->bind_param("si", $newStatus, $userId);
        return $stmt->execute();
    }
}
?>