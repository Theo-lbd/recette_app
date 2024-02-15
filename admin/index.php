<?php
session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php?action=login');
    exit;
}

require_once "../model/Database.php";
require_once "../model/User.php";
require_once "AdminController.php";
require_once "AdminRouter.php";
require_once "../model/Recette.php";



$db = (new Database())->getConnection();
$userModel = new User($db);
$adminController = new AdminController($db, $userModel);
$adminRouter = new AdminRouter($adminController);

$adminRouter->handleRequest();
