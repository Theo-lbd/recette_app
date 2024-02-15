<?php
class Router {
    private $controller;

    /**
     * Constructeur qui initialise le contrôleur.
     * @param object $controller Instance du contrôleur à utiliser pour les requêtes.
     */
    public function __construct($controller) {
        $this->controller = $controller;
    }

    /**
     * Traite la requête et appelle la méthode correspondante du contrôleur.
     */
    public function handleRequest() {
        $action = $_GET['action'] ?? 'home';

        $routes = [
            'home' => 'showHome',
            'add' => 'showAddRecetteForm',
            'delete' => 'deleteRecette',
            'register' => 'register',
            'login' => 'login',
            'logout' => 'logout',
            'my_recipes' => 'showMyRecipes',
            'detail' => 'showRecetteDetail',
            'get_recettes_by_category' => 'getRecettesByCategory',
            'edit' => 'showEditRecetteForm',
            'update' => 'updateRecette',
            'ajouterCommentaire' => 'ajouterCommentaire',
            'supprimerCommentaire' => 'supprimerCommentaire',
            'toggleFavoris' => 'toggleFavoris',
            'favoris' => 'showFavoris',
            'contact' => 'showContactForm', 
            'submitContactForm' => 'submitContactForm',
            'contactSuccess' => 'showContactSuccess',
            'showMessages' => 'showMessages',
            'politique-de-confidentialite' => 'showPolitiqueDeConfidentialite',
            'conditions-dutilisation' => 'showConditionsDUtilisation',

        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'ajouterCommentaire') {
            $recette_id = $_POST['recette_id'] ?? null;
            $user_id = $_SESSION['user_id'] ?? null;
            $commentaire = $_POST['commentaire'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!isset($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
                exit('Erreur de validation CSRF.');
            }

            if ($recette_id && $user_id && $commentaire) {
                $this->controller->{$routes[$action]}($recette_id, $user_id, $commentaire);
            }
            exit;
        }

        if (array_key_exists($action, $routes)) {
            if ($action === 'adminDashboard') {
                if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
                    $this->controller->{$routes[$action]}();
                } else {
                    header('Location: index.php?action=login');
                    exit;
                }
            } else {
                $this->handleAction($action, $routes);
            }
        } else {
            echo "Action non reconnue.";
        }
    }

     /**
     * Appelle la méthode correspondante du contrôleur en fonction de l'action demandée.
     * @param string $action L'action demandée par l'utilisateur.
     * @param array $routes Le tableau associatif des actions et de leurs méthodes de traitement.
     */
    private function handleAction($action, $routes) {
        $actionsWithId = ['delete', 'detail', 'edit', 'supprimerCommentaire'];

        if ($action === 'supprimerCommentaire') {
            $commentaire_id = $_GET['commentaire_id'] ?? null;
            $recette_id = $_GET['recette_id'] ?? null;

            if ($commentaire_id && $recette_id) {
                $this->controller->{$routes[$action]}($commentaire_id, $recette_id);
            } else {
                echo "ID de commentaire ou de recette non spécifié.";
            }
        }elseif (in_array($action, $actionsWithId)) {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $this->controller->{$routes[$action]}($id);
            } else {
                echo "ID de recette non spécifié.";
            }
        } 
        else {
            $this->controller->{$routes[$action]}();
        }
    }
}
