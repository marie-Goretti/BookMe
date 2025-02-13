<?php
include_once 'database.php';

class Livre {
    private $conn;
    private $table_name = "livre";

    public $id_livre;
    public $nom_livre;
    public $resume_livre;
    public $date_publication;
    public $isbn;
    public $id_categorie;
    public $categorie_libelle;
    public $image;
    public $auteurs; // Tableau des auteurs

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllLivres() {
        $query = "
            SELECT l.id_livre, l.nom_livre, l.resume_livre, l.date_publication, l.isbn, l.image,
                   c.libele_categorie,
                   a.nom_auteur, a.prenom_auteur
            FROM livre l
            LEFT JOIN categorie c ON l.id_categorie = c.id_categorie
            LEFT JOIN livre_auteur la ON l.id_livre = la.id_livre
            LEFT JOIN auteur a ON la.id_auteur = a.id_auteur
            ORDER BY l.id_livre DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt;
    }

    
        public function searchLivres($searchTerm) {
            $query = "SELECT l.*, c.libele_categorie, a.nom_auteur, a.prenom_auteur 
                      FROM {$this->table_name} l
                      LEFT JOIN categorie c ON l.id_categorie = c.id_categorie
                      LEFT JOIN publier p ON l.id_livre = p.id_livre
                      LEFT JOIN auteur a ON p.id_auteur = a.id_auteur
                      WHERE l.nom_livre LIKE :search
                         OR c.libele_categorie LIKE :search
                         OR CONCAT(a.nom_auteur, ' ', a.prenom_auteur) LIKE :search
                         OR l.isbn LIKE :search";
    
            $stmt = $this->conn->prepare($query);
            $searchTerm = "%$searchTerm%";
            $stmt->bindParam(':search', $searchTerm);
            $stmt->execute();
    
            return $stmt;
        }
    
    
}    
?>
