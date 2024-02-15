<?php
/**
 * Classe Message pour gérer les interactions avec les messages de contact dans la base de données.
 */
class Message {
    private $db;

    /**
     * Constructeur de la classe Message.
     * @param PDO $db Instance de la connexion à la base de données.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupère tous les messages de contact triés par date de soumission décroissante.
     * @return array Un tableau de tous les messages de contact.
     */
    public function getAllMessages() {
        $query = "SELECT * FROM contact_messages ORDER BY submitted_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les réponses associées à un message spécifique.
     * @param int $messageId L'identifiant du message pour lequel récupérer les réponses.
     * @return array Un tableau de toutes les réponses pour le message spécifié.
     */
    public function getRepliesForMessage($messageId) {
        $query = "SELECT * FROM message_replies WHERE message_id = :message_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':message_id' => $messageId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Soumet une nouvelle réponse à un message de contact.
     * @param int $messageId L'identifiant du message auquel répondre.
     * @param string $reply Le contenu de la réponse.
     * @return bool Retourne true si la réponse a été ajoutée avec succès, sinon false.
     */
    public function submitReply($messageId, $reply) {
        $query = "INSERT INTO message_replies (message_id, reply) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$messageId, $reply]);
    }
}
