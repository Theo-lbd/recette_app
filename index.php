<?php
// Démarrage de la session pour gérer les données de session
session_start(); 
// Génération d'un token CSRF s'il n'existe pas pour protéger contre les attaques CSRF

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once "controller/RecetteController.php";
require_once "model/Database.php";
require_once "model/Recette.php";
require_once "router/router.php";

$db = (new Database())->getConnection();
$controller = new RecetteController($db);
$router = new Router($controller);

$router->handleRequest();
