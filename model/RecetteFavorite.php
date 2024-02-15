<?php
/**
 * Classe RecetteFavorite pour gérer les interactions avec la table des recettes favorites dans la base de données.
 */
class RecetteFavorite {
    private $conn;
    /**
     * Constructeur qui initialise la connexion à la base de données.
     * @param PDO $db Instance de connexion à la base de données.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Constructeur qui initialise la connexion à la base de données.
     * @param PDO $db Instance de connexion à la base de données.
     */
    public function ajouterAuxFavoris($user_id, $recette_id) {
        $query = "INSERT IGNORE INTO recettes_favorites (user_id, recette_id) VALUES (:user_id, :recette_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':recette_id', $recette_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Récupère tous les favoris d'un utilisateur spécifique.
     * @param int $userId ID de l'utilisateur dont on souhaite récupérer les favoris.
     * @return array Liste des ID de recettes favorites de l'utilisateur.
     */
    public function getFavorisByUserId($userId) {
        $query = "SELECT recette_id FROM recettes_favorites WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $favoris = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Récupère uniquement la colonne recette_id
        
        return $favoris;
    }
    
    /**
     * Vérifie si une recette est dans les favoris d'un utilisateur.
     * @param int $user_id ID de l'utilisateur.
     * @param int $recette_id ID de la recette.
     * @return bool Vrai si la recette est un favori, faux sinon.
     */
    public function estFavori($user_id, $recette_id) {
        $query = "SELECT COUNT(*) FROM recettes_favorites WHERE user_id = :user_id AND recette_id = :recette_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':recette_id', $recette_id);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    
        return $count > 0;
    }
    
    /**
     * Supprime une recette des favoris d'un utilisateur.
     * @param int $user_id ID de l'utilisateur.
     * @param int $recette_id ID de la recette à supprimer des favoris.
     * @return bool Résultat de l'opération de suppression.
     */
    public function supprimerDesFavoris($user_id, $recette_id) {
        $query = "DELETE FROM recettes_favorites WHERE user_id = :user_id AND recette_id = :recette_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':recette_id', $recette_id);
    
        return $stmt->execute();
    }
    
}
