<?php
/**
 * Classe AdminRouter gérant le routage des requêtes administratives.
 */
class AdminRouter {
    private $controller;
    /**
     * Constructeur qui initialise le contrôleur.
     * @param AdminController $adminController Contrôleur administratif.
     */
    public function __construct($adminController) {
        $this->controller = $adminController;
    }
    /**
     * Gère la requête entrante et appelle la méthode correspondante du contrôleur.
     */
    public function handleRequest() {
        $this->ensureAdminUser(); // Vérifie si l'utilisateur est un administrateur
        $action = $_GET['action'] ?? 'dashboard';

        $routes = [
            'dashboard' => 'adminDashboard',
            'manageUsers' => 'manageUsers',
            'deleteUser' => 'deleteUser',
            'editUser' => 'editUser',
            'updateUser' => 'updateUser',
            'manageRecipes' => 'manageRecipes',
            'editRecipe' => 'editRecipe',
            'updateRecipe' => 'updateRecipe',
            'deleteRecipe' => 'deleteRecipe',
            'addUser' => 'addUser',
            'showAllMessages' => 'showAllMessages',
            'submitReply' => 'submitReply',
            'logout' => 'logout',
        ];

        if (array_key_exists($action, $routes)) {
            $method = $routes[$action];
            if (method_exists($this->controller, $method)) {
                // Détermine si l'action nécessite un ID et comment l'obtenir
                if (in_array($action, ['deleteUser', 'editUser', 'deleteRecipe', 'editRecipe'])) {
                    $id = $_GET['id'] ?? null; // Pour ces actions, l'ID vient de $_GET
                    if ($id) {
                        $this->controller->$method($id);
                    } else {
                        echo "ID requis pour l'action '{$action}' non spécifié.";
                    }
                } elseif ($action === 'updateUser' || $action === 'updateRecipe') {
                    $id = $_POST['id'] ?? null; // Pour les actions de mise à jour, l'ID peut venir de $_POST
                    if ($id) {
                        $this->controller->$method($id);
                    } else {
                        echo "ID requis pour l'action '{$action}' non spécifié.";
                    }
                } else {
                    $this->controller->$method(); // Pour les autres actions, aucun ID n'est nécessaire
                }
            } else {
                echo "Méthode '{$method}' non trouvée dans le contrôleur d'administration.";
            }
        } else {
            echo "Action '{$action}' non reconnue.";
        }
    }        

    private function ensureAdminUser() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ../index.php?action=login');
            exit;
        }
    }
}
