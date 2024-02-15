<?php
require_once "model/Recette.php";
require_once "model/User.php";
require_once "model/Commentaire.php";
require_once "model/RecetteFavorite.php";
require_once "./admin/model/Message.php";
require_once "model/MessageModel.php";

class RecetteController {
    private $Model;
    private $userModel;
    private $commentaireModel;
    private $recetteFavoriteModel;
    private $db;
    private $Message;
    private $MessageModel;

    /**
     * Constructeur pour initialiser les modèles avec la connexion à la base de données.
     * @param PDO $db Instance de connexion à la base de données.
     */
    public function __construct($db) {
        $this->Model = new Recette($db);
        $this->id = uniqid("ctrl_");
        $this->userModel = new User($db);
        $this->commentaireModel = new Commentaire($db);
        $this->recetteFavoriteModel = new RecetteFavorite($db);
        $this->db = $db;
        $this->Message = new Message($db);
        $this->messageModel = new MessageModel($db);

    }

    /**
     * Affiche la page d'accueil avec toutes les recettes disponibles.
     */
    public function showHome() {
        $stmt = $this->Model->getAllRecettes();
        $recettes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recettes[] = $row;
        }
    
        // Récupère les favoris si l'utilisateur est connecté
        $favoris = [];
        if (isset($_SESSION['user_id'])) {
            $favoris = $this->getFavorisByUserId($_SESSION['user_id']);
        }
    
        // Maintenant, tu passes $favoris à ta vue
        include "view/home.php"; // Assure-toi que ta vue peut accéder à la variable $favoris
    }
    
    /**
     * Affiche le formulaire pour ajouter une nouvelle recette et traite la soumission de ce formulaire.
     */
    public function showAddRecetteForm() {
        $errors = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification du jeton CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Erreur de sécurité: jeton CSRF non valide.');
            }
    
            // Récupération et nettoyage des données du formulaire
            $nom = htmlspecialchars($_POST['nom'] ?? '');
            $ingredients = $_POST['ingredients'] ?? [];
            $ingredientsJson = json_encode(array_map('htmlspecialchars', $ingredients));
            $instructions = htmlspecialchars($_POST['instructions'] ?? '');
            $prep_time = htmlspecialchars($_POST['prep_time'] ?? '');
            $serving_size = htmlspecialchars($_POST['serving_size'] ?? '');
            $image = $_FILES['image'] ?? null;
            $image_path = '';
            $category = htmlspecialchars($_POST['category'] ?? '');
    
            // Validation des données du formulaire
            if (empty($nom)) {
                $errors[] = 'Le nom de la recette est requis.';
            } elseif (strlen($nom) > 255) {
                $errors[] = 'Le nom de la recette ne doit pas dépasser 255 caractères.';
            }
    
            // Validation de la catégorie    
            if (!in_array($category, ['soupe', 'entrée', 'plat', 'dessert'])) {
                $errors[] = 'Catégorie invalide.';
            }
    
            // Traitement et validation de l'image téléchargée
            if (empty($errors) && $image && $image['tmp_name']) {
                if ($image['size'] > 5000000) { // Limite de taille : 5MB
                    $errors[] = 'Le fichier est trop grand.';
                } else {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    $fileType = mime_content_type($image['tmp_name']);
    
                    if (!in_array($fileType, $allowedTypes)) {
                        $errors[] = 'Type de fichier non autorisé.';
                    } else {
                        if (!defined('BASE_PATH')) {
                            define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
                        }
    
                        $uploadDir = BASE_PATH . 'public/uploads/';
                        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
                            $errors[] = 'Impossible de créer le dossier de téléchargement.';
                        }
    
                        $image_path = $uploadDir . uniqid('', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                        if (!move_uploaded_file($image['tmp_name'], $image_path)) {
                            $errors[] = 'Erreur lors du téléchargement de l\'image.';
                            $image_path = ''; 
                        }
                    }
                }
            }
    
            // Si aucune erreur, ajout bdd et rediriger
            if (empty($errors)) {
                if (isset($_SESSION['user_id'])) {
                    $this->Model->insertRecette($nom, $ingredientsJson, $instructions, $_SESSION['user_id'], $image_path, $prep_time, $serving_size, $category);
                    header("Location: index.php");
                    exit;
                } else {
                    $errors[] = 'Vous devez être connecté pour ajouter une recette.';
                }
            }
        }
        include "view/addRecette.php";
    }
      
    /**
     * Affiche le formulaire pour éditer une recette existante.
     * @param int $id ID de la recette à éditer.
     */
    public function showEditRecetteForm($id) {
        $recette = $this->Model->getRecetteById($id);
        if (!$recette || ($_SESSION['user_id'] != $recette['user_id'] && !$_SESSION['is_admin'])) {
            header("Location: index.php?error=not_authorized");
            exit;
        }
        include "view/editRecette.php";      
    }

    /**
     * Met à jour les informations d'une recette après soumission du formulaire d'édition.
     */    
    public function updateRecette() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification du jeton CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Erreur de sécurité: jeton CSRF non valide.');
            }
    
            // Récupérer l'ID de la recette
            $id = $_POST['id'] ?? null;
            $recetteExistante = $this->Model->getRecetteById($id);
            if (!$recetteExistante || ($_SESSION['user_id'] != $recetteExistante['user_id'] && !$_SESSION['is_admin'])) {
                header("Location: index.php?error=not_authorized");
                exit;
            }
    
            // Récupérer et valider les données mises à jour
            $nom = htmlspecialchars($_POST['nom'] ?? '');
            $ingredients = isset($_POST['ingredients']) ? array_map('htmlspecialchars', $_POST['ingredients']) : [];
            $ingredientsJson = json_encode($ingredients);
            $instructions = htmlspecialchars($_POST['instructions'] ?? '');
            $prep_time = htmlspecialchars($_POST['prep_time'] ?? '');
            $serving_size = htmlspecialchars($_POST['serving_size'] ?? '');
            $category = htmlspecialchars($_POST['category'] ?? '');
    
            // Initialiser le tableau d'erreurs
            $errors = [];
    
            // Validation pour le nom
            if (empty($nom)) {
                $errors[] = "Le nom de la recette est requis.";
            }
            if (strlen($nom) > 255) {
                $errors[] = "Le nom de la recette ne doit pas dépasser 255 caractères.";
            }
    
            // Validation pour les ingrédients
            if (empty($ingredients)) {
                $errors[] = "Au moins un ingrédient est requis.";
            }
    
            // Validation pour les instructions
            if (empty($instructions)) {
                $errors[] = "Les instructions sont requises.";
            }
    
            // Validation pour le temps de préparation
            if (!is_numeric($prep_time) || $prep_time <= 0) {
                $errors[] = "Le temps de préparation doit être un nombre positif.";
            }
    
            // Validation pour la taille de portion
            if (!is_numeric($serving_size) || $serving_size <= 0) {
                $errors[] = "La taille de portion doit être un nombre positif.";
            }
    
            // Validation pour la catégorie
            if (empty($category)) {
                $errors[] = "La catégorie est requise.";
            }
    
            // Gestion et validation de l'image téléchargée
            $image_path = $recetteExistante['image_path']; // Conserver le chemin existant si aucune nouvelle image n'est fournie
            if ($image && $image['tmp_name']) {
                // Assurez-vous que le fichier est une image
                if (getimagesize($image['tmp_name']) === false) {
                    $errors[] = "Le fichier doit être une image.";
                }
    
                // Vérifiez la taille du fichier (exemple : limite à 5MB)
                if ($image['size'] > 5000000) {
                    $errors[] = "L'image ne doit pas dépasser 5MB.";
                }
                
                // Déplacez l'image téléchargée vers le nouveau chemin
                if (!move_uploaded_file($image['tmp_name'], $new_image_path)) {
                    $errors[] = "Erreur lors du téléchargement de l'image.";
                } else {
                    $image_path = $new_image_path;
                }
            }
    
            // Si aucune erreur, mettre à jour la recette dans la base de données
            if (empty($errors)) {
                if ($this->Model->updateRecette($id, $nom, $ingredientsJson, $instructions, $image_path, $prep_time, $serving_size, $category)) {
                    header("Location: index.php?action=detail&id=" . $id);
                    exit;
                } else {
                    $errors[] = 'Erreur lors de la mise à jour de la recette.';
                }
            }
    
            // S'il y a des erreurs, réaffichez le formulaire avec les messages d'erreur
            include "view/editRecette.php"; // Inclure la vue avec les données actuelles
        }
    }

    /**
     * Supprime une recette spécifiée par son ID.
     * @param int $id ID de la recette à supprimer.
     */
    public function deleteRecette($id) {
        $recetteUserId = $this->Model->getUserIdByRecetteId($id);
    
        // Vérifier si l'utilisateur est connecté et est le créateur de la recette
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recetteUserId) {
            $this->Model->deleteRecette($id);
            header("Location: index.php");
        } else {
            header("Location: index.php?error=not_allowed");
        }
    }
    
    /**
     * Enregistre un nouvel utilisateur et traite la soumission du formulaire d'inscription.
     */
    public function register() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? ''; // Récupérer la confirmation du mot de passe
    
            // Vérifier si les mots de passe correspondent
            if ($password !== $confirm_password) {
                $errors[] = 'Les mots de passe ne correspondent pas.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $wasCreated = $this->userModel->createUser($firstname, $lastname, $email, $hashed_password);
    
                if ($wasCreated) {
                    header("Location: index.php?action=login");
                    exit;
                } else {
                    $errors[] = 'Cet e-mail est déjà enregistré. Veuillez en choisir un autre.';
                }
            }
        }
    
        include "view/signup.php";
    }
    
    /**
     * Connecte un utilisateur et traite la soumission du formulaire de connexion.
     */
    public function login() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->userModel->getUserByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
    
                if ($this->userModel->isAdmin($user['id'])) {
                    $_SESSION['is_admin'] = true;
                    header('Location: admin/index.php'); // Redirection vers la partie admin
                    exit;
                } else {
                    header("Location: index.php"); // Redirection pour un utilisateur normal
                    exit;
                }
            } else {
                $errors[] = 'Erreur dans l\'authentification';
            }
        }
        include "view/login.php";
    }
    
    /**
     * Déconnecte l'utilisateur en détruisant la session.
     */
    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php");
    }

    /**
     * Affiche les recettes de l'utilisateur connecté.
     */
    public function showMyRecipes() {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }

        // Récupere les recettes de l'utilisateur
        $recipes = $this->Model->getRecipesByUserId ($_SESSION['user_id']);
        include "view/my_recipes.php";
    }

    /**
     * Affiche le détail d'une recette spécifique, y compris les commentaires associés.
     * @param int $id ID de la recette dont le détail doit être affiché.
     */
    public function showRecetteDetail($id) {
        $recette = $this->Model->getRecetteById($id);
        if ($recette) {
            $commentaires = $this->commentaireModel->getCommentairesParRecette($id);

            include "view/recetteDetail.php";
        } else {
            echo "Recette non trouvée.";
        }
    }

    /**
     * Filtre et affiche les recettes selon une catégorie spécifique.
     */
    public function getRecettesByCategory() {
        $category = $_GET['category'] ?? 'all';
        $recettes = $this->Model->getRecettesByCategory($category);
        
        $json_data = json_encode($recettes);
        
        if(json_last_error() != JSON_ERROR_NONE) {
            error_log('JSON Error: ' . json_last_error_msg());
            echo json_encode(['error' => 'An error occurred while fetching recipes.']);
            exit();
        }
        
        // Send JSON data
        header('Content-Type: application/json');
        echo $json_data;
    }

    /**
     * Ajoute un commentaire à une recette spécifique.
     * @param int $recette_id ID de la recette à laquelle le commentaire doit être ajouté.
     * @param int $user_id ID de l'utilisateur ajoutant le commentaire.
     * @param string $commentaire Texte du commentaire à ajouter.
     */
    public function ajouterCommentaire($recette_id, $user_id, $commentaire) {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            exit('Erreur de sécurité: jeton CSRF non valide.');
        }
    
        if (empty($commentaire)) {
            echo 'Erreur: le commentaire ne peut pas être vide.';
            return false;
        }
    
        if (empty($user_id)) {
            echo 'Erreur: utilisateur non identifié.';
            return false;
        }
    
        // Nettoie le commentaire pour éviter les attaques XSS
        $commentaireSecurise = htmlspecialchars($commentaire);
    
        $ajoutReussi = $this->commentaireModel->ajouterCommentaire($recette_id, $user_id, $commentaireSecurise);
    
        if ($ajoutReussi) {
            header('Location: index.php?action=detail&id=' . $recette_id);
            exit;
        } else {
            echo 'Erreur: impossible d\'ajouter le commentaire.';
        }
    }
    
    /**
     * Supprime un commentaire spécifié par son ID.
     * @param int $commentaire_id ID du commentaire à supprimer.
     * @param int $recette_id ID de la recette associée au commentaire.
     */
    public function supprimerCommentaire($commentaire_id, $recette_id) {
        if (!isset($_SESSION['user_id'])) {
            exit('Vous devez être connecté pour effectuer cette action.');
        }
    
        // Vérifie si l'utilisateur est bien l'auteur du commentaire
        $commentaire = $this->commentaireModel->getCommentaireById($commentaire_id);
        if ($_SESSION['user_id'] != $commentaire['user_id']) {
            exit('Vous n\'avez pas le droit de supprimer ce commentaire.');
        }
    
        $this->commentaireModel->supprimerCommentaire($commentaire_id);
    
        header('Location: index.php?action=detail&id=' . $recette_id);
        exit;
    }
    
    /**
     * Ajoute ou supprime une recette des favoris de l'utilisateur connecté.
     */
    public function ajouterAuxFavoris() {
        $data = json_decode(file_get_contents('php://input'), true);
        $user_id = $_SESSION['user_id'] ?? null;
        $recette_id = $data['recetteId'] ?? null;
    
        if ($user_id && $recette_id) {
            $resultat = $this->recetteFavoriteModel->ajouterAuxFavoris($user_id, $recette_id);
            echo json_encode(['success' => $resultat]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Manque d\'informations']);
        }
        exit;
    }    
    
    /**
     * Récupère la liste des recettes favorites de l'utilisateur connecté.
     * @param int $userId ID de l'utilisateur dont les favoris sont demandés.
     * @return array Liste des ID des recettes favorites de l'utilisateur.
     */
    public function getFavorisByUserId($userId) {
        return $this->recetteFavoriteModel->getFavorisByUserId($userId);
    }
    
    /**
     * Ajoute ou supprime une recette des favoris de l'utilisateur connecté.
     */
    public function toggleFavoris() {
        $userId = $_SESSION['user_id'] ?? null;
        $data = json_decode(file_get_contents('php://input'), true);
        $recetteId = $data['recetteId'] ?? null;
    
        if ($userId && $recetteId) {
            $isFavori = $this->recetteFavoriteModel->estFavori($userId, $recetteId);
            if ($isFavori) {
                $result = $this->recetteFavoriteModel->supprimerDesFavoris($userId, $recetteId);
            } else {
                $result = $this->recetteFavoriteModel->ajouterAuxFavoris($userId, $recetteId);
            }
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
        }
        exit;
    }
    
    /**
     * Affiche la page des recettes favorites de l'utilisateur connecté.
     */
    public function showFavoris() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
    
        $userId = $_SESSION['user_id'];
        $favoris = $this->recetteFavoriteModel->getFavorisByUserId($userId);
    
        $recettesFavoris = [];
        foreach ($favoris as $recetteId) {
            $recette = $this->Model->getRecetteById($recetteId);
            if ($recette) {
                $recettesFavoris[] = $recette;
            }
        }
    
        include "view/favoris.php"; 
    }

    /**
     * Affiche le formulaire de contact et traite la soumission de ce formulaire.
     */
    public function showContactForm() {
        include 'view/contactForm.php';
    }

    /**
     * Traite la soumission du formulaire de contact.
     */
    public function submitContactForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                exit('Validation CSRF échouée.');
            }
    
            if (!isset($_SESSION['user_id'])) {
                die('Vous devez être connecté pour envoyer un message.');
            }
    
            // Récupere les données du formulaire
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $userId = $_SESSION['user_id'];
    
            if ($this->messageModel->submitContactForm($userId, $name, $email, $subject, $message)) {
                header('Location: index.php?action=contactSuccess');
            } else {
                echo "Erreur lors de l'envoi du message.";
            }
        }
    }
    
    /**
     * Affiche la page de confirmation après l'envoi d'un message via le formulaire de contact.
     */
    public function showContactSuccess() {
        include 'view/contactSuccess.php'; // Assurez-vous que ce fichier existe et contient le message de succès
    }

    /**
     * Affiche les messages envoyés par l'utilisateur connecté.
     */
    public function showMessages() {
        if (!isset($_SESSION['user_id'])) {
            die('Vous devez être connecté pour voir vos messages.');
        }
    
        $userId = $_SESSION['user_id'];
        $messages = $this->messageModel->getAllMessagesByUserId($userId);
    
        foreach ($messages as $key => $message) {
            $replies = $this->messageModel->getRepliesForMessage($message['id']);
            $messages[$key]['replies'] = $replies;
        }
    
        include 'view/showMessages.php'; 
    }
    
    public function showPolitiqueDeConfidentialite() {
        include 'view/politiqueConfidentialite.php';
    }

    public function showConditionsDUtilisation() {
        include 'view/conditionUtilisation.php';
    }
}