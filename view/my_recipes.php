<?php include 'header.php'; ?>

<h1>Mes recettes</h1>
<?php foreach ($recipes as $recette): ?>
    <?php include 'recipeCard.php'; ?>
<?php endforeach; ?>


<?php include 'footer.php'; ?>