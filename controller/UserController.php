<?php
require_once "model/User.php";

class UserController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function register() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // TODO: Add validation for the inputs
    
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $wasCreated = $this->userModel->createUser($firstname, $lastname, $email, $hashed_password);
            
            if ($wasCreated) {
                header("Location: index.php?action=login");
                exit;
            } else {
                $errors[] = 'This email is already registered. Please choose another one.';
            }
        }
        include "view/signup.php";
    }

    public function login() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // TODO: Add validation for the inputs
    
            $user = $this->userModel->getUserByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php");
                exit;
            } else {
                $errors[] = 'Invalid login credentials';
            }
        }
        include "view/login.php";
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php");
    }
}
