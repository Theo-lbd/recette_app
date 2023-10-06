<?php
class Router {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function handleRequest() {
        $controllerName = $_GET['controller'] ?? 'HomePage';
        $actionName = $_GET['action'] ?? 'showHome';  // Default to showHome action

        $controllerClass = ucfirst($controllerName) . "Controller";

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass($this->db);  // Pass the database connection to the controller

            if (method_exists($controller, $actionName)) {
                $controller->$actionName();
            } else {
                echo "Action not found: " . htmlspecialchars($actionName);
            }
        } else {
            echo "Controller not found: " . htmlspecialchars($controllerClass);
        }
    }
}