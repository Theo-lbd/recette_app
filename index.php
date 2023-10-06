<?php
session_start(); 
define('BASE_PATH', __DIR__ . '/');

require_once "controller/RecetteController.php";
require_once "model/Database.php";
require_once "model/Recette.php";
require_once "router/router.php";

$db = (new Database())->getConnection();
$controller = new RecetteController($db);
$router = new Router($controller);

$router->handleRequest();

