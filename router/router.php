<?php
    class Router {
        private $controller;
    
        public function __construct($controller) {
            $this->controller = $controller;
        }
    
        public function handleRequest() {
            $action = $_GET['action'] ?? 'home';
    
            switch ($action) {
                case 'home':
                default:
                    $this->controller->showHome();
                    break;
                case 'add':
                    $this->controller->showAddRecetteForm();
                    break;
                case 'delete':
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        $this->controller->deleteRecette($id);
                    } else {
                        echo "ID de recette non spécifié.";
                    }
                    break;
                case 'register':
                    $this->controller->register();
                    break;
                case 'login':
                    $this->controller->login();
                    break;
                case 'logout':
                    $this->controller->logout();
                    break;
                case 'my_recipes':
                    $this->controller->showMyRecipes();
                    break;
                // Ajoutez d'autres cas au besoin...
            }
        }
    }
    
    