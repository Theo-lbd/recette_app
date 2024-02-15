<?php 
/**
 * Classe Commentaire pour gérer les interactions avec la table des commentaires dans la base de données.
 */
class Commentaire {
    private $conn;
    /**
     * Constructeur qui initialise la connexion à la base de données.
     * @param PDO $db Instance de connexion à la base de données.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Ajoute un nouveau commentaire à la base de données.
     * @param int $recette_id ID de la recette à laquelle le commentaire est associé.
     * @param int $user_id ID de l'utilisateur qui poste le commentaire.
     * @param string $commentaireTexte Le texte du commentaire.
     * @return bool Résultat de l'opération d'insertion.
     */
    public function ajouterCommentaire($recette_id, $user_id, $commentaireTexte) {
        $query = "INSERT INTO commentaires (recette_id, user_id, commentaire) VALUES (:recette_id, :user_id, :commentaire)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':recette_id', $recette_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':commentaire', $commentaireTexte, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Récupère tous les commentaires associés à une recette spécifique.
     * @param int $recette_id ID de la recette pour laquelle les commentaires sont récupérés.
     * @return array Liste des commentaires pour la recette spécifiée.
     */
    public function getCommentairesParRecette($recette_id) {
        $query = "SELECT commentaires.*, users.firstname, users.lastname FROM commentaires 
              JOIN users ON commentaires.user_id = users.id 
              WHERE recette_id = :recette_id 
              ORDER BY commentaires.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':recette_id', $recette_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    
    /**
     * Récupère un commentaire spécifique par son ID.
     * @param int $id ID du commentaire à récupérer.
     * @return array Informations sur le commentaire spécifié.
     */
    public function getCommentaireById($id) {
        $query = "SELECT * FROM commentaires WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Supprime un commentaire de la base de données.
     * @param int $id ID du commentaire à supprimer.
     */
    public function supprimerCommentaire($id) {
        $query = "DELETE FROM commentaires WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
}
