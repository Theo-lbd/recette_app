<?php
/**
 * Classe UserAdmin pour gérer les opérations administratives sur les utilisateurs.
 */
class UserAdmin {
    private $db;

    /**
     * Constructeur de la classe UserAdmin.
     * @param PDO $db Instance de la connexion à la base de données.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupère tous les utilisateurs.
     * @return array Un tableau de tous les utilisateurs.
     */
    public function getAllUsers() {
        $query = "SELECT * FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime un utilisateur spécifié par son identifiant.
     * @param int $userId L'identifiant de l'utilisateur à supprimer.
     */
    public function deleteUser($userId) {
        $query = "DELETE FROM users WHERE id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Met à jour les informations d'un utilisateur.
     * @param int $userId L'identifiant de l'utilisateur à mettre à jour.
     * @param string $firstname Le prénom de l'utilisateur.
     * @param string $lastname Le nom de l'utilisateur.
     * @param string $email L'email de l'utilisateur.
     * @return bool Retourne true si la mise à jour a réussi, sinon false.
     */
    public function updateUser($userId, $firstname, $lastname, $email) {
        $query = "UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email WHERE id = :userId";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    
        return $stmt->execute(); // Exécute la requête et renvoie true si réussie, false sinon
    }

    /**
     * Récupère un utilisateur par son identifiant.
     * @param int $userId L'identifiant de l'utilisateur à récupérer.
     * @return array Les informations de l'utilisateur spécifié.
     */
    public function getUserById($userId) {
        $query = "SELECT * FROM users WHERE id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
