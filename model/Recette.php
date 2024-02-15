<?php
/**
 * Classe Recette pour gérer les interactions avec la table des recettes dans la base de données.
 */
class Recette {
    private $conn;

    /**
     * Constructeur qui initialise la connexion à la base de données.
     * @param PDO $db Instance de connexion à la base de données.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Récupère toutes les recettes de la base de données.
     * @return PDOStatement Résultat de la requête.
     */
    public function getAllRecettes() {
        $query = "SELECT * FROM recettes ORDER BY date_creation DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            // Vous pourriez logger l'erreur ici ou afficher un message d'erreur à l'utilisateur
            error_log($e->getMessage());
            return false;
        }
    }
    

    /**
     * Insère une nouvelle recette dans la base de données.
     * @param string $nom Nom de la recette.
     * @param string $ingredients Ingrédients de la recette au format JSON.
     * @param string $instructions Instructions de préparation de la recette.
     * @param int $user_id ID de l'utilisateur qui ajoute la recette.
     * @param string $image_path Chemin de l'image associée à la recette.
     * @param int $prep_time Temps de préparation de la recette.
     * @param int $serving_size Nombre de portions de la recette.
     * @param string $category Catégorie de la recette.
     * @return bool Résultat de l'opération d'insertion.
     */
    public function insertRecette($nom, $ingredients, $instructions, $user_id, $image_path, $prep_time, $serving_size, $category) {
        $query = "INSERT INTO recettes (nom, ingredients, instructions, user_id, image_path, prep_time, serving_size, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $nom);
            $stmt->bindParam(2, $ingredients);
            $stmt->bindParam(3, $instructions);
            $stmt->bindParam(4, $user_id, PDO::PARAM_INT);
            $stmt->bindParam(5, $image_path);
            $stmt->bindParam(6, $prep_time, PDO::PARAM_INT);
            $stmt->bindParam(7, $serving_size, PDO::PARAM_INT);
            $stmt->bindParam(8, $category); 
            $stmt->execute();
            $this->conn->commit();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log($e->getMessage());
            return false;
        }
        
        return true;
    }
    
    /**
     * Met à jour une recette spécifiée par son ID.
     * @param int $id ID de la recette à mettre à jour.
     * @param string $nom Nouveau nom de la recette.
     * @param string $ingredients Nouveaux ingrédients de la recette au format JSON.
     * @param string $instructions Nouvelles instructions de la recette.
     * @param string $image_path Nouveau chemin de l'image de la recette.
     * @param int $prep_time Nouveau temps de préparation de la recette.
     * @param int $serving_size Nouvelle taille de portion de la recette.
     * @param string $category Nouvelle catégorie de la recette.
     * @return bool Résultat de l'opération de mise à jour.
     */
    public function updateRecette($id, $nom, $ingredients, $instructions, $image_path, $prep_time, $serving_size, $category) {
        $query = "UPDATE recettes SET nom = ?, ingredients = ?, instructions = ?, image_path = ?, prep_time = ?, serving_size = ?, category = ? WHERE id = ?";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $nom);
            $stmt->bindParam(2, $ingredients);
            $stmt->bindParam(3, $instructions);
            $stmt->bindParam(4, $image_path);
            $stmt->bindParam(5, $prep_time);
            $stmt->bindParam(6, $serving_size);
            $stmt->bindParam(7, $category);
            $stmt->bindParam(8, $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Supprime une recette de la base de données.
     * @param int $id ID de la recette à supprimer.
     */
    public function deleteRecette($id) {
        $query = "DELETE FROM recettes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Récupère l'ID de l'utilisateur ayant ajouté une recette spécifique.
     * @param int $recetteId ID de la recette concernée.
     * @return int|null ID de l'utilisateur ayant ajouté la recette, ou null si non trouvé.
     */
    public function getUserIdByRecetteId($recetteId) {
        $query = "SELECT user_id FROM recettes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $recetteId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['user_id'] ?? null;
    }
    
    /**
     * Récupère toutes les recettes ajoutées par un utilisateur spécifique.
     * @param int $user_id ID de l'utilisateur dont on souhaite récupérer les recettes.
     * @return array Liste des recettes de l'utilisateur spécifié.
     */    
    public function getRecipesByUserId($user_id) {
        $query = "SELECT * FROM recettes WHERE user_id = ? ORDER BY date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une recette spécifique par son ID.
     * @param int $id ID de la recette à récupérer.
     * @return array Informations sur la recette spécifiée.
     */
    public function getRecetteById($id) {
        $query = "SELECT * FROM recettes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les recettes appartenant à une catégorie spécifique.
     * @param string $category Catégorie des recettes à récupérer.
     * @return array Liste des recettes de la catégorie spécifiée.
     */
    public function getRecettesByCategory($category) {
        if ($category === 'all') {
            $query = "SELECT * FROM recettes";
            $stmt = $this->conn->prepare($query);
        } else {
            $query = "SELECT * FROM recettes WHERE category = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $category, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Effectue une recherche de recettes basée sur un terme de recherche.
     * @param string $query Terme de recherche.
     * @return array Liste des recettes correspondant au terme de recherche.
     */
    public function searchRecettes($query) {
        $searchTerm = '%' . $query . '%';
        $stmt = $this->conn->prepare("
            SELECT * FROM recettes 
            WHERE nom LIKE :searchTerm 
            OR JSON_SEARCH(ingredients, 'one', :searchTerm) IS NOT NULL
        ");
        $stmt->bindParam(':searchTerm', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}