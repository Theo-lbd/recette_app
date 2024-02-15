<?php if (!empty($recettes)): ?>
    <?php foreach ($recettes as $recette): ?>
        <?php include 'recipeCard.php'; ?>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucune recette à afficher pour le moment.</p>
<?php endif; ?>
