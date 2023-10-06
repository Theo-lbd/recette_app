<?php
require_once "model/Recette.php";

class RecetteController {
    private $model;

    public function __construct($db) {
        $this->model = new Recette($db);
    }


    public function showAddRecetteForm() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $nom = $_POST['nom'] ?? '';
            $ingredients = $_POST['ingredients'] ?? [];
            $ingredientsJson = json_encode($ingredients);
            $instructions = $_POST['instructions'] ?? '';
            $prep_time = $_POST['prep_time'] ?? '';
            $serving_size = $_POST['serving_size'] ?? '';
            $image = $_FILES['image'] ?? null;
            $image_path = '';
            $category = $_POST['category'] ?? '';
    
            // Valider les données
            if (empty($nom)) {
                $errors[] = 'Le nom de la recette est requis.';
            } elseif (strlen($nom) > 255) {
                $errors[] = 'Le nom de la recette ne doit pas dépasser 255 caractères.';
            }
    
            if (empty($ingredients)) {
                $errors[] = 'Au moins un ingrédient est requis.';
            } else {
                foreach ($ingredients as $ingredient) {
                    if (empty($ingredient)) {
                        $errors[] = 'Les ingrédients ne doivent pas être vides.';
                        break;
                    } elseif (strlen($ingredient) > 255) {
                        $errors[] = 'Les ingrédients ne doivent pas dépasser 255 caractères.';
                        break;
                    }
                }
            }
    
            if (empty($instructions)) {
                $errors[] = 'Les instructions sont requises.';
            }
    
            if (empty($prep_time) || !is_numeric($prep_time) || $prep_time <= 0) {
                $errors[] = 'Le temps de préparation est invalide.';
            }
    
            if (empty($serving_size) || !is_numeric($serving_size) || $serving_size <= 0) {
                $errors[] = 'Le nombre de personnes est invalide.';
            }
    
            // Validate and move the uploaded image
            if ($image && $image['tmp_name']) {
                if ($image['size'] > 5000000) { // size limit: 5MB
                    $errors[] = 'Le fichier est trop grand.';
                } else {
                    if (!defined('BASE_PATH')) {
                        define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
                    }
    
                    $uploadDir = BASE_PATH . 'public/uploads/';
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0777, true)) {
                            $errors[] = 'Impossible de créer le dossier de téléchargement.';
                        }
                    }
                    
                    $image_path = $uploadDir . uniqid('', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
                    if (!move_uploaded_file($image['tmp_name'], $image_path)) {
                        $errors[] = 'Erreur lors du téléchargement de l\'image.';
                        $image_path = ''; // Reset image path if upload failed
                    } else {
                        error_log("Image uploaded to: " . $image_path);
                    }
                }
            }
            
            if (!in_array($category, ['soupe', 'entrée', 'plat', 'dessert'])) {
                $errors[] = 'Catégorie invalide.';
            }

            // Si aucune erreur, ajouter à la base de données et rediriger
            if (empty($errors)) {
                if(isset($_SESSION['user_id'])) {
                    $this->model->insertRecette($nom, $ingredientsJson, $instructions, $_SESSION['user_id'], $image_path, $prep_time, $serving_size, $category);
                    header("Location: index.php");
                    exit;
                } else {
                    $errors[] = 'Vous devez être connecté pour ajouter une recette.';
                }
            }
    }
        
        // Afficher le formulaire d'ajout de recette (avec ou sans erreurs)
        include "view/addRecette.php";
    }

    public function deleteRecette($id) {
        // Récupérer l'user_id de la recette
        $recetteUserId = $this->model->getUserIdByRecetteId($id);
    
        // Vérifier si l'utilisateur est connecté et est le créateur de la recette
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recetteUserId) {
            $this->model->deleteRecette($id);
            header("Location: index.php");
        } else {
            // Gérer l'erreur (rediriger vers une page d'erreur ou afficher un message)
            header("Location: index.php?error=not_allowed");
        }
    }

    public function showMyRecipes() {
        // Vérifiez si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    // Récupérez les recettes de l'utilisateur
    $recipes = $this->model->getRecipesByUserId($_SESSION['user_id']);

    // Affichez la vue
    include "view/my_recipes.php";
    }
}