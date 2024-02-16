<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Application de Recettes</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap">
    <link rel="stylesheet" href="../../public/css/main.css">
</head>
<body>
    <header class="top-nav">
        <nav>
            <a href="../index.php" class="admin-title-link">
                <div class="admin-title"><?php echo isset($_SESSION['is_admin']) ? 'Administration' : 'Logo'; ?></div>
            </a>
            <input id="menu-toggle" type="checkbox" />
            <label class='menu-button-container' for="menu-toggle">
                <div class='menu-button'></div>
            </label>
            <ul class="menu">
            <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Liens pour les utilisateurs normaux -->
                    <li><a class="button-a" href="../index.php?action=add">Ajouter une recette</a></li>
                    <li><a class="button-a" href="../index.php?action=my_recipes">Mes recettes</a></li>
                    <li><a class="button-a" href="../index.php?action=favoris">Mes Favoris</a></li>
                    <li><a class="button-a" href="../index.php?action=contact">Contactez-nous</a></li>
                    <li><a class="button-a" href="../index.php?action=logout">Déconnexion</a></li>
                    <?php else: ?>
                    <!-- Liens pour les visiteurs non connectés -->
                    <li><a class="button-a" href="../index.php?action=login">Connexion</a></li>
                    <li><a class="button-a" href="../index.php?action=register">Inscription</a></li>
                    <?php endif; ?>
            
            </ul>
        </nav>
    </header>
