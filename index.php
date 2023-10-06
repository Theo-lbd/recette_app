<?php
session_start(); 
define('BASE_PATH', __DIR__ . '/');

spl_autoload_register(function ($class_name) {
    include 'controller/' . $class_name . '.php';
});

require_once "model/Database.php";
require_once "router/router.php";

$db = (new Database())->getConnection();
$router = new Router($db);
$router->handleRequest();


