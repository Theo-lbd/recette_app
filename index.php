<?php
// Démarrage de la session pour gérer les données de session
session_start();

// Durée d'expiration de la session (1 heure)
$maxSessionTime = 60 * 60;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $maxSessionTime)) {
    // Dernière activité il y a plus d'1 heure, la session expire
    session_unset();     // Supprime les variables de session
    session_destroy();   // Détruit la session
    header("Location: login.php"); // Redirige vers la page de connexion
    exit;
}

// Met à jour le temps de la dernière activité à chaque chargement de page
$_SESSION['last_activity'] = time();

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
