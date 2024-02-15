<?php
require_once '../model/Database.php';
require_once '../model/Recette.php';


$db = (new Database())->getConnection();
$recetteModel = new Recette($db);

// Récupération de la requête de recherche
$query = $_GET['query'] ?? '';

// Effectuer la recherche sur bdd
$results = $recetteModel->searchRecettes($query);

// Affichage des résultats sous forme de liens cliquables
foreach ($results as $recette) {
    echo '<a href="index.php?action=detail&id=' . $recette['id'] . '">' . htmlspecialchars($recette['nom']) . '</a><br>';
}
