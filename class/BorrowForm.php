<?php
class BorrowForm {
    private $book;
    private $user;
    private $id_livre;
    private $email_utilisateur;

    public function __construct($book, $user, $id_livre, $email_utilisateur) {
        $this->book = $book;
        $this->user = $user;
        $this->id_livre = $id_livre;
        $this->email_utilisateur = $email_utilisateur;
    }

    public function render() {
        $livre = $this->book->getBookDetails($this->id_livre);
        if (!$livre) {
            header("Location: dashboardu.php");
            exit();
        }

        $user = $this->user->getUserInfo($this->email_utilisateur);
        if (!$user) {
            header("Location: connexion.php");
            exit();
        }

        $id_utilisateur = $user['id_utilisateur'];
        $prenom_utilisateur = $user['prenom_utilisateur'];
        $date_emprunt = date('Y-m-d');
        $date_retour = date('Y-m-d', strtotime("+15 days"));
        
    }
}
?>