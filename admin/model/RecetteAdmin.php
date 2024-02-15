<?php
/**
 * Classe RecetteAdmin pour gérer les opérations administratives sur les recettes.
 */
class RecetteAdmin {
    private $db;

    /**
     * Constructeur de la classe RecetteAdmin.
     * @param PDO $db Instance de la connexion à la base de données.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupère toutes les recettes triées par date de création décroissante.
     * @return array Un tableau de toutes les recettes.
     */
    public function getAllRecettes() {
        $query = "SELECT * FROM recettes ORDER BY date_creation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime une recette spécifiée par son identifiant.
     * @param int $recipeId L'identifiant de la recette à supprimer.
     */
    public function deleteRecette($recipeId) {
        $query = "DELETE FROM recettes WHERE id = :recipeId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
    }

}
