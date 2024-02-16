<?php
// Inclusion des dépendances
require_once "../model/Recette.php";
require_once "model/UserAdmin.php";
require_once "model/RecetteAdmin.php";
require_once "model/Message.php";
require_once "../model/MessageModel.php";
require_once "../model/User.php";

/**
 * Classe AdminController gérant les actions administratives.
 */
class AdminController {
    private $userModel;
    private $recipeModel;
    private $message;
    private $recetteModel;
    private $messageModel;
    private $user;

    /**
     * Constructeur pour initialiser les modèles avec la connexion à la base de données.
     * @param PDO $db Instance de connexion à la base de données.
     */
    public function __construct($db) {
        $this->userModel = new UserAdmin($db);
        $this->recipeModel = new RecetteAdmin($db);
        $this->message = new Message($db);
        $this->recetteModel = new Recette($db);
        $this->messageModel = new MessageModel($db);
        $this->user = new User($db);
    }

    /**
     * Affiche le tableau de bord administrateur.
     */
    public function adminDashboard() {
        include "view/adminDashboard.php";
    }

    /**
     * Gère l'affichage et la gestion des utilisateurs.
     */
    public function manageUsers() {
        $users = $this->userModel->getAllUsers(); // Récupère tous les utilisateurs
        include "view/manageUsers.php";
    }

    /**
     * Supprime un utilisateur spécifié par son ID.
     * @param int $userId ID de l'utilisateur à supprimer.
     */
    public function deleteUser($userId) {
        if (filter_var($userId, FILTER_VALIDATE_INT)) {
            $this->userModel->deleteUser($userId); // Supprime l'utilisateur
            header("Location: index.php?action=manageUsers");
        } else {
            echo "ID d'utilisateur invalide.";
        }
    }

    /**
     * Prépare l'édition d'un utilisateur.
     */
    public function editUser() {
        $userId = $_GET['id'] ?? null;
        if ($userId) {
            $user = $this->userModel->getUserById($userId);
            if ($user) {
                include "view/editUser.php";
            } else {
                echo "Utilisateur non trouvé.";
            }
        } else {
            echo "ID d'utilisateur non spécifié.";
        }
    }

    /**
     * Met à jour les informations d'un utilisateur après soumission du formulaire.
     */
    public function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $userId = $_POST['id'] ?? null;
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';

            if ($userId) {
                $result = $this->userModel->updateUser($userId, $firstname, $lastname, $email);
                if ($result) {
                    echo "<p>Utilisateur mis à jour avec succès.</p>";
                    header("Refresh:2; url=index.php?action=manageUsers");
                } else {
                    echo "Erreur lors de la mise à jour de l'utilisateur.";
                }
            } else {
                echo "ID d'utilisateur non spécifié.";
            }
        }
    }

    public function manageRecipes() {
        $recipes = $this->recipeModel->getAllRecettes();
        include "view/manageRecipes.php";
    }

    public function editRecipe() {
        $id = $_GET['id'] ?? null; //stocker l'identifiant de la recette
        if ($id) {
            $recette = $this->recetteModel->getRecetteById($id);
            if ($recette) {
                include "view/editRecipe.php";
            } else {
                echo "Recette non trouvée.";
            }
        } else {
            echo "ID de recette non spécifié.";
        }
    }
    
    public function updateRecipe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération de l'ID de la recette depuis le formulaire
            $id = $_POST['id'] ?? null;
            $nom = $_POST['nom'] ?? '';
            $ingredients = $_POST['ingredients'] ?? '';
            $instructions = $_POST['instructions'] ?? '';
            $prep_time = $_POST['prep_time'] ?? '';
            $serving_size = $_POST['serving_size'] ?? '';
            $category = $_POST['category'] ?? '';
    
            // Récupération du chemin de l'ancienne image depuis la base de données
            $recetteExistante = $this->recetteModel->getRecetteById($id);
            $image_path = $recetteExistante['image_path'] ?? null;
            $ingredients = array_filter($_POST['ingredients'], function($ingredient) {
                return !empty(trim($ingredient));
            });
            // Traitement de la nouvelle image téléchargée
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['image'];
                $uploadDir = '../public/uploads/';
                $imageName = uniqid('recette_', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                $imagePath = $uploadDir . $imageName;
    
                if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                    $image_path = $imagePath;
                } else {
                    echo "Erreur lors du téléchargement de l'image.";
                }
            }
    
            // Mise à jour de la recette avec les informations fournies
            $result = $this->recetteModel->updateRecette($id, $nom, json_encode($ingredients), $instructions, $image_path, $prep_time, $serving_size, $category);
            if ($result) {
                header("Location: index.php?action=manageRecipes&update=success");
                exit;
            } else {
                echo "Erreur lors de la mise à jour de la recette.";
            }
        } else {
            header("Location: index.php?action=manageRecipes");
            exit;
        }
    }
    
    /**
     * Supprime une recette spécifiée par son ID.
     * @param int $recipeId ID de la recette à supprimer.
     */
    public function deleteRecipe($recipeId) {
        $this->recipeModel->deleteRecette($recipeId);
        header("Location: index.php?action=manageRecipes");
    }

    /**
     * Affiche tous les messages reçus via le formulaire de contact dans l'interface d'administration.
     */
    public function showAllMessages() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            die('Accès réservé aux administrateurs.');
        }
    
        $messages = $this->message->getAllMessages();
        foreach ($messages as $key => $message) {
            $replies = $this->message->getRepliesForMessage($message['id']);
            $messages[$key]['replies'] = $replies;
        }
        include 'view/showAllMessages.php';
    }
    
    /**
     * Traite la soumission d'une réponse à un message spécifique.
     */
    public function submitReply() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageId = $_POST['message_id'] ?? null;
            $reply = $_POST['reply'] ?? null;
    
            if ($messageId && $reply) {
                $success = $this->message->submitReply($messageId, $reply);
    
                header('Content-Type: application/json');
                echo json_encode(['success' => $success]);
                exit;
            }
        }
    
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
    
    /**
     * Ajoute un nouvel utilisateur via le formulaire d'administration.
     */
    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
    
            $result = $this->user->createUser($firstname, $lastname, $email, $password);
            if ($result) {
                echo "<p>Utilisateur ajouté avec succès.</p>";
                header("Refresh:2; url=index.php?action=manageUsers");
            } else {
                echo "Erreur lors de l'ajout de l'utilisateur. L'email est peut-être déjà utilisé.";
            }
        }
        include 'view/addUser.php';

    }

    /**
     * Déconnecte l'utilisateur et détruit la session.
     */
    public function logout() {
        // Détruire la session
        session_start();
        session_destroy();
    
        // Rediriger vers la page de connexion ou la page d'accueil
        header('Location: index.php?action=login');
        exit;
    }
}