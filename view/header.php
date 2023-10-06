<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mon Application de Recettes</title>
    <!-- Vous pouvez ajouter ici d'autres éléments du <head> comme les liens vers les CSS, etc. -->
</head>
<body>
    <h1>Bienvenue sur Mon Application de Recettes</h1>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="index.php?controller=recette&action=add">Ajouter une recette</a></li>
                <li><a href="index.php?controller=recette&action=my_recipes">Mes recettes</a></li>
                <li><a href="index.php?controller=user&action=logout">Logout</a></li>
            <?php else: ?>
                <li><a href="index.php?controller=user&action=login">Login</a></li>
                <li><a href="index.php?controller=user&action=register">Sign Up</a></li>
            <?php endif; ?>
        </ul>

    </nav>