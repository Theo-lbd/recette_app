<?php
class Recette {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllRecettes() {
        $query = "SELECT * FROM recettes ORDER BY date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function insertRecette($nom, $ingredients, $instructions, $user_id, $image_path, $prep_time, $serving_size, $category) {
        $query = "INSERT INTO recettes (nom, ingredients, instructions, user_id, image_path, prep_time, serving_size, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
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
    }
    
    

    public function deleteRecette($id) {
        $query = "DELETE FROM recettes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getUserIdByRecetteId($recetteId) {
        $query = "SELECT user_id FROM recettes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $recetteId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['user_id'] ?? null;
    }
    

    // Dans votre modèle
public function getRecipesByUserId($user_id) {
    $query = "SELECT * FROM recettes WHERE user_id = ? ORDER BY date_creation DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Ajoutez ici d'autres méthodes pour gérer les recettes (ajouter, modifier, supprimer, etc.)
}
