<?php
/**
 * Classe User pour gérer les interactions avec la table des utilisateurs dans la base de données.
 */
class User {
    private $conn;
    /**
     * Constructeur qui initialise la connexion à la base de données.
     * @param PDO $db Instance de connexion à la base de données.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crée un nouvel utilisateur dans la base de données.
     * @param string $firstname Prénom de l'utilisateur.
     * @param string $lastname Nom de l'utilisateur.
     * @param string $email Email de l'utilisateur.
     * @param string $hashed_password Mot de passe haché de l'utilisateur.
     * @return bool Résultat de l'opération d'insertion.
     */
    public function createUser($firstname, $lastname, $email, $hashed_password) {
        $query = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $firstname);
        $stmt->bindParam(2, $lastname);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $hashed_password);
        
        try {
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return false;  // Faux su email en double
            }
            throw $e;
        }
    }
    
    /**
     * Récupère un utilisateur spécifique par son email.
     * @param string $email Email de l'utilisateur à récupérer.
     * @return array|false Informations sur l'utilisateur ou false en cas d'erreur.
     */
    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Vérifie si un utilisateur est administrateur.
     * @param int $userId ID de l'utilisateur à vérifier.
     * @return bool Vrai si l'utilisateur est administrateur, faux sinon.
     */
    public function isAdmin($userId) {
        $query = "SELECT role FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $role = $stmt->fetchColumn();
    
        return $role === 'is_admin';
    }
    
    /**
     * Récupère tous les utilisateurs de la base de données.
     * @return array Liste de tous les utilisateurs.
     */
    public function getAllUsers() {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gère les recettes liées à un utilisateur supprimé en les réaffectant à un autre utilisateur.
     * @param int $userId ID de l'utilisateur dont les recettes doivent être réaffectées.
     */
    private function handleRelatedRecipes($userId) {
        // Réassigner les recettes à un autre utilisateur par exemple, l'administrateur avec l'ID 1
        $stmt = $this->conn->prepare("UPDATE recettes SET user_id = 1 WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }    
        
}