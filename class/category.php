<?php
class Category {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllCategories() {
        $sql = "SELECT id_categorie, libele_categorie FROM categorie";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBooksByCategory($categoryId) {
        $sql = "SELECT livre.id_livre, livre.nom_livre, livre.image_livre, 
                       auteur.nom_auteur, auteur.prenom_auteur 
                FROM livre
                INNER JOIN auteur ON livre.id_auteur = auteur.id_auteur
                WHERE livre.id_categorie = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>