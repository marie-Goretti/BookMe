<?php
// Book.php
class Book {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function searchBooks($search) {
        $sql = "SELECT livre.id_livre, livre.nom_livre, livre.resume_livre, livre.date_publication, livre.image_livre, 
                       livre.statut, auteur.nom_auteur, auteur.prenom_auteur
                FROM livre
                INNER JOIN auteur ON livre.id_auteur = auteur.id_auteur
                WHERE livre.nom_livre LIKE ? 
                OR auteur.nom_auteur LIKE ? 
                OR auteur.prenom_auteur LIKE ? 
                OR livre.isbn LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $search_param = "%$search%";
        $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    public function checkBorrowStatus($livre_id, $user_id) {
        $check_sql = "SELECT * FROM emprunter WHERE id_livre = ? AND id_utilisateur = ?";
        $check_stmt = $this->conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $livre_id, $user_id);
        $check_stmt->execute();
        return $check_stmt->get_result();
    }

    public function getBookDetails($id_livre) {
        $sql = "SELECT livre.nom_livre, livre.image_livre, livre.resume_livre, auteur.nom_auteur, auteur.prenom_auteur 
                FROM livre 
                INNER JOIN auteur ON livre.id_auteur = auteur.id_auteur 
                WHERE livre.id_livre = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_livre);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>