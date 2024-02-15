<?php 
/**
 * Classe MessageModel pour gérer les interactions avec la table des messages dans la base de données.
 */
class MessageModel {
    private $db;

    /**
     * Constructeur qui initialise la connexion à la base de données.
     * @param PDO $db Instance de connexion à la base de données.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupère tous les messages envoyés par un utilisateur spécifique.
     * @param int $userId ID de l'utilisateur dont on souhaite récupérer les messages.
     * @return array Liste des messages de l'utilisateur spécifié.
     */
    public function getAllMessagesByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM contact_messages WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Soumet un formulaire de contact et insère le message dans la base de données.
     * @param int $userId ID de l'utilisateur soumettant le formulaire.
     * @param string $name Nom de l'utilisateur.
     * @param string $email Email de l'utilisateur.
     * @param string $subject Sujet du message.
     * @param string $message Corps du message.
     * @return bool Résultat de l'opération d'insertion.
     */
    public function submitContactForm($userId, $name, $email, $subject, $message) {
        $stmt = $this->db->prepare("INSERT INTO contact_messages (user_id, name, email, subject, message) VALUES (:user_id, :name, :email, :subject, :message)");
        return $stmt->execute([
            ':user_id' => $userId,
            ':name' => $name,
            ':email' => $email,
            ':subject' => $subject,
            ':message' => $message
        ]);
    }

    /**
     * Récupère toutes les réponses pour un message spécifique.
     * @return array Liste des réponses au message spécifié.
     */
    public function getRepliesForMessage($messageId) {
        $stmt = $this->db->prepare("SELECT * FROM message_replies WHERE message_id = :message_id");
        $stmt->execute([':message_id' => $messageId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
